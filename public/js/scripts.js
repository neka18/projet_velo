/*!
* Start Bootstrap - Bare v5.0.5 (https://startbootstrap.com/template/bare)
* Copyright 2013-2021 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-bare/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project

$(document).ready(() => {
    $('a.flag').click(e => {
        e.preventDefault();
        $.get(e.currentTarget.href, res => {
            $(e.currentTarget).parents('.well').append(res);
        });
    });
});
