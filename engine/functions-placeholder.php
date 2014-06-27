<?php

namespace Blockify\Placeholder;


function create( &$document )
{
    if( ! is_array($document) ) {
        return $document;
    }

    array_walk( $document, __FUNCTION__ );

    if( \Blockify\Internal\is_array_assoc($document) ) {

        // If @list defined in struct
        if( array_key_exists('@list', $document) )
        {
            if( array_key_exists('@value', $document['@list']) ) {
                $document['@list'] = $document['@list']['@value'];
            } else {
                placehold_document_data($document['@list'], rand(1, 10));
            }
        }

        // If @type defined in struct
        if( array_key_exists('@type', $document) )
        {
            global $blockify;
            $schema = $blockify->schema;

            document_fix_type( $document );

            // If a schema.org type is provided, let's placehold the data
            if( array_key_exists($document['@type'], $schema['types']) )
            {
                foreach( $schema['types'][$document['@type']]['properties'] as $key ) {
                    if( ! array_key_exists($key, $document) ) {
                    $document[$key] = null;
                    }
                }
            }

            placehold_document_data( $document );

        }

    }

    return $document;
}

function placehold_document_data(&$document, $size = false)
{
    $generatedDocuments = array();
    $single = $size === false || !is_int($size);

    if(!$single) {
        document_fix_type( $document );
    }

    $count = $single ? 1 : $size;

    switch( $document['@type'] ) {
        case 'Product':
            $appleStore = json_decode(file_get_contents("https://itunes.apple.com/gb/rss/topmacapps/limit={$count}/json"), true);
            $entries = $single ?
                [$appleStore['feed']['entry']] :
                $appleStore['feed']['entry'];
            foreach( $entries as $entry ) {
                $generatedDocuments[] = array_replace_recursive($document, apple_store_to_schema($entry) );
            }
            break;
        case 'Person':
            $randomuserResp = json_decode( file_get_contents('http://api.randomuser.me/?results=' . $count), true );
            foreach( $randomuserResp['results'] as $result ) {
                $generatedDocuments[] = array_replace_recursive($document, random_user_to_schema($result['user']) );
            }
            break;
        case 'Article':
            for ($i=0; $i < $count; $i++) {
                $generatedDocuments[] = array_replace_recursive($document, [
                    'articleBody' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                ]);
            }
        default:
            $generatedDocuments[] = $document;
            break;
    }

    // Blend with defaults
    foreach ($generatedDocuments as &$generatedDocument) {
        $generatedDocument = \Blockify\Internal\array_replace_null_recursive($generatedDocument, [
            'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.',
            'image' => 'holder.js/512x512/auto',
            'name' => 'Hello World',
            'url' => '#'
        ]);
    }

    $document = $single ? $generatedDocuments[0] : $generatedDocuments;
}

function document_fix_type( &$document )
{
    if( is_array($document['@type']) ) {
        $document['@type'] = $document['@type'][array_rand($document['@type'])];
    }
}

function random_user_to_schema( $randomuser )
{
    $name = $randomuser['name'];
    foreach ($name as &$namePart) {
        $namePart = ucfirst( $namePart );
    }
    return [
        'name' => $name['title'] . ' ' . $name['first'] . ' ' . $name['last'],
        'givenName' => $name['first'],
        'familyName' => $name['last'],
        'email' => $randomuser['email'],
        'gender' => $randomuser['gender'],
        'image' => $randomuser['picture'],
        'telephone' => $randomuser['cell']
    ];
}


function apple_store_to_schema( $entry )
{
    $imageCount = sizeof($entry['im:image']);
    return [
        'name' => $entry['im:name']['label'],
        'image' => $entry['im:image'][$imageCount - 1]['label'],
        //'description' => $entry['summary']['label']
        'offers' => [
            '@type' => 'Offer',
            'price' => $entry['im:price']['attributes']['amount'],
            'priceCurrency' => $entry['im:price']['attributes']['currency']
        ]
    ];
}

/*

Example $entry for @apple_store_to_schema

{
    "im:name": {
        "label": "OS X Mavericks"
    },
    "im:image": [
        {
            "label": "http://a1048.phobos.apple.com/us/r30/Purple/v4/af/36/ac/af36ac2b-2c35-e4b2-0369-6a5a54893d17/ProductPageIcon.53x53-50.png",
            "attributes": {
                "height": "53"
            }
        },
        {
            "label": "http://a1564.phobos.apple.com/us/r30/Purple/v4/af/36/ac/af36ac2b-2c35-e4b2-0369-6a5a54893d17/ProductPageIcon.75x75-65.png",
            "attributes": {
                "height": "75"
            }
        },
        {
            "label": "http://a1161.phobos.apple.com/us/r30/Purple/v4/af/36/ac/af36ac2b-2c35-e4b2-0369-6a5a54893d17/ProductPageIcon.100x100-75.png",
            "attributes": {
                "height": "100"
            }
        }
    ],
    "summary": {
        "label": "With more than 200 new features, OS X Mavericks brings Maps and iBooks to the Mac, introduces Finder Tabs and Tags, enhances multi-display support and includes an all-new version of Safari. The latest release of OS X also adds new core technologies that deliver breakthrough power efficiency and responsiveness.\n\niBooks\n• Download and read books from the iBooks Store.\n• Pick up where you left off. iCloud keeps your current page up to date across all your devices. \n• Swipe through Multi-Touch books with interactive features, diagrams, photos, videos and more.\n• Keep multiple books open while using other apps — great for writing an essay or doing research. \n\nMaps\n• Send directions from your Mac to your iPhone and use voice navigation when you’re on the go.\n• Explore selected cities in stunning, photo-realistic 3D with Flyover.\n• See detailed directions, real-time traffic and alternative routes.\n• Find restaurants, shops and other places of interest with local search in Maps.\n\nCalendar\n• Create new events in a snap with the new, streamlined event inspector. \n• Enter event locations fast with address auto-completion. \n• Add walking or driving travel time to your event so you know when to leave. \n• See a map of your event’s location, as well as the weather forecast for that day.\n• See holidays and Facebook events in Calendar.\n\nSafari\n• Use Shared Links to discover new, interesting links posted by people you follow on Twitter and LinkedIn.\n• Browse longer thanks to new core technologies that boost energy efficiency.\n• Easily access your bookmarks, Reading List and Shared Links in the new Sidebar. \n• Protect your online privacy with new tracking-prevention features. \n\niCloud Keychain\n• Don’t worry about remembering passwords — iCloud Keychain fills them in so you don’t have to. \n• Keep your website passwords, credit card numbers and Wi-Fi passwords up to date across your trusted devices. Robust 256-bit AES encryption helps keep your information safe.\n• Sign in once to all your mail, contacts, calendar and other Internet accounts, and iCloud pushes them to all your Mac computers. \n\nMultiple Displays\n• Just plug in a second display to use it with your Mac — no configuration required.\n• Access the Dock and the menu bars on each display.\n• Use full-screen apps on any or all of your displays.\n•Use your HDTV as a second display with Apple TV.\n\nNotifications\n• Reply to mail or messages straight from a notification, without having to leave the app you’re using.\n• Receive notifications for incoming FaceTime calls and reply with an iMessage or set a call-back reminder.\n• Receive notifications from websites, even when Safari isn’t running. \n\nFinder Tabs\n• De-clutter your desktop by consolidating multiple Finder windows into one.\n• Move files between your tabs by simply dragging and dropping them. \n• Select a custom view — icon, list or column — for each of your tabs. \n• Use tabs with full-screen Finder to organise and access all your files and folders.\n\nTags\n• Organise files with tags no matter where they’re located — in iCloud or on your Mac.\n• Give a document as many tags as you want.\n• Click a tag in the Finder sidebar to see all the files with that tag. \n\nAdvanced Technologies \n• With energy-saving core technologies in OS X Mavericks, you can surf the web longer on a single charge.\n• Watching iTunes HD video is now more efficient, so you can watch more video when you’re not plugged in.\n• App Nap regulates applications you’re not using so they consume less energy.\n\niCloud Keychain on iPhone, iPad and iPod touch requires iOS 7.0.3.\n\nMultiple Display with Apple TV and an HDTV works with Apple TV (2nd generation or newer), iMac (mid 2011 or newer), Mac mini (mid 2011 or newer), MacBook Air (mid 2011 or newer), MacBook Pro (early 2011 or newer) and Mac Pro (late 2013). \n\nSome features require an Apple ID and/or compatible Internet access; additional fees and terms apply. "
    },
    "im:price": {
        "label": "Free",
        "attributes": {
            "amount": "0.00000",
            "currency": "GBP"
        }
    },
    "im:contentType": {
        "attributes": {
            "term": "Application",
            "label": "Application"
        }
    },
    "rights": {
        "label": "© 2013 Apple, Inc."
    },
    "title": {
        "label": "OS X Mavericks - Apple"
    },
    "link": {
        "attributes": {
            "rel": "alternate",
            "type": "text/html",
            "href": "https://itunes.apple.com/gb/app/os-x-mavericks/id675248567?mt=12&uo=2"
        }
    },
    "id": {
        "label": "https://itunes.apple.com/gb/app/os-x-mavericks/id675248567?mt=12&uo=2",
        "attributes": {
            "im:id": "675248567",
            "im:bundleId": "com.apple.InstallAssistant.Mavericks"
        }
    },
    "im:artist": {
        "label": "Apple",
        "attributes": {
            "href": "https://itunes.apple.com/gb/artist/apple/id284417353?mt=12&uo=2"
        }
    },
    "category": {
        "attributes": {
            "im:id": "12014",
            "term": "Productivity",
            "scheme": "https://itunes.apple.com/gb/genre/mac-productivity/id12014?mt=12&uo=2",
            "label": "Productivity"
        }
    },
    "im:releaseDate": {
        "label": "2013-10-22T11:56:27-07:00",
        "attributes": {
            "label": "22 October 2013"
        }
    }
}
 */
