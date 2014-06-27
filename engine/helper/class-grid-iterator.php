<?php

    /*
     * Iterator for generating Bootstrap Grids
     */

    define( 'GRID_AUTO_ROWS',      1 << 0 );
    define( 'GRID_CENTER_COLUMNS', 1 << 1 );
    define( 'GRID_VERTICAL_FLIP',  1 << 2 );

    class BlockifyGridIterator extends ArrayIterator
    {
        public $flags;

        private $position;
        private $size_break;
        private $column_widths;
        private $max_column_size;

        function __construct( $array, $column = 4, $flags = null )
        {
            parent::__construct( $array );

            // Default Flags
            if( empty($flags) ) {
                $flags = GRID_AUTO_ROWS | GRID_CENTER_COLUMNS;
            }
            $this->position = 0;
            $this->size_break = 0;
            $this->max_column_size = 0;
            $this->flags = $flags;
            $this->column_widths = $column;

            if( is_array($column) ) {
                foreach( $column as $key => $value ) {
                    if( $value > $this->max_column_size ) {
                        $this->max_column_size = $value;
                    }
                }
            } else {
                $this->calculateColumns( $column );
            }
        }

        private function calculateColumns( $max_columns )
        {
            $max_column_size = 1;
            $count = parent::count();

            if( ! is_int($max_columns) ) {
                $max_columns = 4;
            }

            $max_columns = min( $count, $max_columns );

            // Look for a divisor
            for($w = $max_columns; $w > 0; $w--) {
                $a = $count / $w;
                // Ignore low columns when we have a lot of items
                $skip = $count > $max_columns && $w <= $max_columns / 2;
                if( $a == floor($a) && !($skip) ) {
                    $max_column_size = $w;
                    break;
                }
            }

            // Look for a close divisor
            if( $max_column_size == 1 ) {
                $close_divisors = [];

                for($w = $max_columns; $w > 1; $w--) {
                    $a = $w * floor($count / $w);
                    $b = $count - $a;
                    if( $b == floor($b) ) {
                        $close_divisors[$w + $b] = ['width' => $w, 'break' => $b];
                    }
                }

                // Find the best close divisor by largest width and remainder
                if( !empty($close_divisors) ) {
                    $data = $close_divisors[max(array_keys($close_divisors))];
                    $this->size_break = $data['break'];
                    $max_column_size = $data['width'];
                }

                unset($close_divisors);
            }

            // Calculate Widths
            $width = 12 / $max_column_size;
            $this->max_column_size = $max_column_size;
            $this->column_widths = array(
                'xs' => 10,
                'sm' => min(12, $width * 2), // fix this
                'md' => $width,
                'lg' => $width
            );
        }

        public function column()
        {
            $class = '';

            $offset_reset = false;

            foreach( $this->column_widths as $key => $col ) {

                $vertical_check = ($this->flags & GRID_VERTICAL_FLIP) ? $this->position : $this->remainder() - 1;
                if( $vertical_check < $this->size_break ) {
                    $col = 12 / $this->size_break;
                }

                $class .= " col-{$key}-{$col}";

                if( $this->flags & GRID_CENTER_COLUMNS ) {
                    $offset = 0;
                    if( $col != 12 && $col > 6 ) {
                        $offset = floor((12 - $col) / 2);
                    }
                    if( $offset_reset || $offset != 0 ) {
                        $class .= " col-{$key}-offset-" . $offset;
                    }
                    $offset_reset = $offset !== 0;
                }
            }

            return trim($class);
        }

        public function remainder()
        {
            return parent::count() - $this->position;
        }

        private function rowTime()
        {
            return $this->flags & GRID_AUTO_ROWS
                    &&($this->position == 0 // Start of grid
                    || $this->remainder() == 0); // End of grid
        }

        private function columnBreak( $column_size = null )
        {
            if( !GRID_AUTO_ROWS ) {
                return false;
            }

            $position = $this->position;
            $vertical_check = $this->remainder();
            if( $this->flags & GRID_VERTICAL_FLIP ) {
                $position -= $this->size_break;
                $vertical_check = $this->position;
            }

            return($position % $column_size == 0 // End of row
                    || $vertical_check == $this->size_break); // Start/end of remainder row
        }

        public function rewind() {
            $this->position = 0;
            parent::rewind();
        }

        public function seek( $position ) {
            $this->position = $position;
            parent::seek($position);
        }

        public function valid() {
            $valid = parent::valid();

            $rowTime = $this->rowTime();

            if( $valid && ($rowTime || $this->columnBreak($this->max_column_size)) ) {
                echo '<div class="row">';
            }

            if( ! $rowTime ) {
                // Column clearing
                $clearclass = '';
                foreach( $this->column_widths as $key => $col ) {
                    $column_size = floor( 12 / $col );
                    if( $column_size > 1 && $this->columnBreak( $column_size ) ) {
                        $clearclass .= " visible-{$key}";
                    }
                }
                if( ! empty($clearclass) ) {
                    echo "<div class=\"clearfix{$clearclass}\"></div>";
                }
            }

            return $valid;
        }

        public function next() {
            $this->position++;
            parent::next();

            if( $this->rowTime() || $this->columnBreak($this->max_column_size) ) {
                echo '</div>';
            }
        }

    }
