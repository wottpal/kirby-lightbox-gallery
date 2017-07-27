<?php

/* Helper Functions for Column-Calculation */

/**
 * [columns description]
 */
function columns($min, $max, $count) {
  if ($count < $min) return $count;

  // Find column-width with least amount of rows & diff
  $least_rows = PHP_INT_MAX;
  $least_diff = PHP_INT_MAX;
  $col = PHP_INT_MAX;
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
 * [columnClass description]
 */
function columnClass($prefix, $min, $max, $count, $i) {
  $cols = columns($min, $max, $count);
  $diff = $cols - ($count % $cols);
  // If distribution isn't evenly enlarge items in first row
  if ($diff != 0 && $i < ($cols - $diff)) {
    $cols = $cols - $diff;
  }
  return $prefix . $cols;
}
