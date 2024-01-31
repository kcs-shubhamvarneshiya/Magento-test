define(['jquery'], function ($) {
    'use strict';

    let responses = {};
    let lastResponse = null;

    return function (actionUrl) {
        if (actionUrl === undefined) {
            return new Promise(function (resolve, reject) {
                lastResponse !== false ? resolve(lastResponse) : reject(new Error('Unable to retrieve result'))
            })
        }
        if (responses[actionUrl] !== undefined) {
            return new Promise(function (resolve, reject) {
                responses[actionUrl] !== false ? resolve(responses[actionUrl]) : reject('Unable to retrieve result')
            })
        }
        return $.ajax({
            type: "GET",
            url: actionUrl
        }).done(function (response) {
            responses[actionUrl] = response;
            lastResponse = response
        }).fail(function () {
            responses[actionUrl] = false;
            lastResponse = false;
        })
    }
})
