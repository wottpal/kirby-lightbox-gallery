<?php
/**
* Helper Functions for Column-Calculation
*/


/**
* This function returns true if the given string is a valid columns-definition.
* It must contain exactly two int values.
*/
function columnsFromString($cols_string) {
  $cols = explode(' ', trim($cols_string));
  if (count($cols) < 1 || intval($cols[0]) === 0) return false;

  if (count($cols) === 1 || intval($cols[1]) === 0) {
    return [ 'min' => intval($cols[0]), 'max' => intval($cols[0]) ];
  }

  return [ 'min' => intval($cols[0]), 'max' => intval($cols[1]) ];
}


/**
* This function calculates a nice column-count for the given images-count within
* the given ['$min', '$max'] range.
*
* It tries to minimizes two parameters (in this order):
*   (1) amount of rows,
*   (2) amount-difference of images in first/last row
*/
function columns($min, $max, $stretch, $count) {
  if ($stretch && $count < $min) return $count;

  // Find column-width with least amount of rows & diff
  $least_rows = PHP_INT_MAX;
  $least_diff = PHP_INT_MAX;
  $col = 1;
  for ($c = $max; $c >= $min; $c--) {
    $diff = $c - ($count % $c);
    $rows = ceil($count / $c);
    if ($rows < $least_rows || $rows == $least_rows && $diff < $least_diff) {
      $least_rows = $rows;
      $least_diff = $diff;
      $col = $c;
    }
  }

  return $col;
}


/**
* After determination of the necessary column-count with 'columns(..)' this
* function returns the necessary class for the '$i'-th image.
* These are not the same for all because the last / first (default) row gets
* stretched to fill the full width even with less items than columns.
*/
function columnClass($prefix, $min, $max, $stretch, $stretch_last, $count, $i) {
  $cols = columns($min, $max, $stretch, $count);

  // If stretching is enabled stretch items in either the first / last row
  $diff = $cols - ($count % $cols);
  if ($stretch && $diff != 0) {
    $stretch_first_now = !$stretch_last && ($i < ($cols - $diff));
    $stretch_last_now = $stretch_last && ($i >= ($count - ($cols - $diff) ));
    // Use larger columns if stretching applies for the given image
    if ($stretch_first_now || $stretch_last_now) {
      $cols = $cols - $diff;
    }
  }

  // Return amount of columns appended to the given class-prefix
  return $prefix . $cols;
}
