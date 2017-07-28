<?php
/**
* Helper Functions for Column-Calculation
*/


/**
* This function calculates a nice column-count for the given images-count within
* the given ['$min', '$max'] range.
*
* It tries to minimizes two parameters (in this order):
*   (1) amount of rows,
*   (2) amount-difference of images in first/last row
*/
function columns($min, $max, $count) {
  if ($count < $min) return $count;

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
  $cols = columns($min, $max, $count);

  // If stretching is enabled stretch items in either the first / last row
  $diff = $cols - ($count % $cols);
  if ($stretch && $diff != 0) {
    $stretch_first_now = !$stretch_last && ($i < ($cols - $diff));
    $stretch_last_now = $stretch_last && ($i >= ($count - $diff - 1));
    // Use larger columns if stretching applies for the given image
    if ($stretch_first_now || $stretch_last_now) {
      $cols = $cols - $diff;
    }
  }

  // Return amount of columns appended to the given class-prefix
  return $prefix . $cols;
}
