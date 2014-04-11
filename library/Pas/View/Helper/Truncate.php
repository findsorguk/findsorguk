<?php
class Pas_View_Helper_Truncate extends Zend_View_Helper_Abstract {
        private $_string;
        private $_length;
        private $_postfix;
        private $_cutatspace = true;

        public function truncate($string) {
            $this->_string = trim($string);
            $this->_defaultValues();
            return $this;
        }

        private function _defaultValues() {
            $this->toLength(100);
            $this->withPostfix('&#0133;');
         }

        public function midword() {
            $this->_cutatspace = false;
            return $this;
        }

        public function toLength($int) {
            $this->_length = (int) $int;
            return $this;
        }
        public function withPostfix($str) {
            $this->_postfix = $str;
            return $this;
        }

        public function render() {
            // Return empty string if max length < 1
            if ($this->_length < 1) {
                return '';
            }

            // Return full string if max length >= string length
            if ($this->_length >= strlen($this->_string)) {
                return $this->_string;
            }

            // Return truncated string
            if ($this->_cutatspace) {
                while (strlen($this->_string) > $this->_length) {
                    $cutPos = strrpos($this->_string, ' ', -1);
                    if ($cutPos === false) {
                        // no spaces left, whole string truncated
                        return '';
                    }
                    $this->_string = trim(substr($this->_string, 0, $cutPos));
                }
            } else {
                $this->_string = trim(substr($this->_string, 0, $this->_length));
            }
            return $this->_string . $this->_postfix;
        }

        public function __toString() {
            return $this->render();
        }
}