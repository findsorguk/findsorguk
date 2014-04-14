// JavaScript Document
jQuery(document).ready(function($) {
$('#broadperiod').linkedSelect('http://localhost/redev/ajax/catsperiod/term','#category',{firstOption: 'Please select a category'});
$('#broadperiod').linkedSelect('http://localhost/redev/jax/rulersperiod/term','#ruler',{firstOption: 'Please select a ruler'});
});