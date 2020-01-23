/*
 * App.js
 * Ultima-admin
 * Design_mylife
 */
$(function () {
    "use strict";
    //preloader
    $(window).load(function () {
        $('#preloader').fadeOut('slow', function () {
            $(this).remove();
        });
    });
//sidenav collapse
    $('.button-collapse').sideNav({
        menuWidth: 240,
        edge: 'left',
        closeOnClick: true
    });
    $('.right-sidebar-button').sideNav({
        menuWidth: 240,
        edge: 'right',
        closeOnClick: false
    });
    $(".dropdown-button").dropdown({});
    //fixed toolbar button
    $('.fixed-action-btn').openFAB();
    $('.fixed-action-btn').closeFAB();
//parallax
    $('.parallax').parallax();
    //modal
    $('.modal').modal();
    //select
    $('select').material_select();
    //date picker
    // $('.datepicker').pickadate({
    //    defaultDate: today,
    //     selectMonths: true, // Creates a dropdown to control month
    //     selectYears: 15 // Creates a dropdown of 15 years to control year
    // });
    //autocomplete
    $('input.autocomplete').autocomplete({
        data: {
            "Apple": null,
            "Microsoft": null,
            "Google": 'http://placehold.it/250x250'
        }
    });
    //character Counter
    $('input#input_text, textarea#textarea1').characterCounter();
});
