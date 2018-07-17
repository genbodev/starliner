import '../scss/base.scss';
import $ from 'jquery';
import authForm from '../templates/authForm.hbs';
import requestForm from '../templates/requestForm.hbs';
import table from '../templates/table.hbs';
import list from '../templates/list.hbs';

function Main() {
    let app = this;

    this.init = function () {
        app.checkAuth();
        app.addEventHelpButton();
        app.addEventGetOperationsButton();
        app.addEventGetTypesButton();
    };

    this.checkAuth = function () {
        let get = $.ajax({
            method: 'GET',
            url: '/api/auth/',
            dataType: 'json'
        });

        get.done(function (data/*, textStatus, jqXHR*/) {
            if (data.status === false) {
                app.showAuthForm();
            } else {
                app.showRequestForm();
                app.showLogoutButton();
            }
        });

        get.fail(function (/*jqXHR, textStatus, errorThrow*/) {
            //
        });
    };

    this.addEventHelpButton = function () {
        $('#help-button')[0].addEventListener('click', function () {
            $('#login').val('test');
            $('#psw').val('bYKoDO2it');
            $('#terminal').val('htk_test');
            $('#represent_id').val('22400');
            $('#train').val('016А');
            $('#from').val('МОСКВА');
            $('#to').val('МУРМАНСК');
            $('#day').val('1');
            $('#month').val('7');
        }, false);
    };

    this.addEventGetOperationsButton = function() {
        $('#operations-button').on('click', app.getSoapOperations);
    };
    this.addEventGetTypesButton = function() {
        $('#types-button').on('click', app.getSoapTypes);
    };

    this.showAuthForm = function () {
        let html, form = $('#form-wrapper');
        html = authForm();
        $(form).html(html);
        let authMessage = $('#auth-message');
        $('form').submit(function (e) {
            e.preventDefault();
            $(authMessage).removeClass('auth-message-alert');
            $(authMessage).html('Please, wait...');
            $(authMessage).show();
            let form = $(this);
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json'
            }).done(function (data/*, textStatus, jqXHR*/) {
                let callout = $('#form').find('.callout');
                if (data.status === false) {
                    $(callout).removeClass('secondary');
                    $(callout).addClass('alert');
                    $(authMessage).addClass('auth-message-alert');
                    $(authMessage).html('Wrong data');
                    $(authMessage).show();
                } else {
                    app.showRequestForm();
                    app.showLogoutButton();
                }
            }).fail(function (/*jqXHR, textStatus, errorThrow*/) {
                //
            });
        });
    };

    this.showRequestForm = function () {
        let html, form = $('#form-wrapper');
        html = requestForm();
        $(form).html(html);
        $('form').submit(function (e) {
            e.preventDefault();
            let listwrp = $('#list-wrapper');
            $(listwrp).removeClass('auth-message-alert');
            $(listwrp).html('Please, wait...');
            let form = $(this);
            let formData = new FormData();
            formData.append('train', $('#train').val());
            formData.append('from', $('#from').val());
            formData.append('to', $('#to').val());
            formData.append('day', $('#day').val());
            formData.append('month', $('#month').val());
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false
            }).done(function (data/*, textStatus, jqXHR*/) {
                if (data.status === true) {
                    html = table(data.response);
                    $(listwrp).html(html);
                } else {
                    $(listwrp).addClass('auth-message-alert');
                    $(listwrp).html('Wrong data...');
                }
            }).fail(function (/*jqXHR, textStatus, errorThrow*/) {
                $(listwrp).addClass('auth-message-alert');
                $(listwrp).html('Wrong data...');
            });
        });
    };

    this.showLogoutButton = function () {
        let logoutButton = $('#logout-button');
        $(logoutButton).show();
        $(logoutButton).on('click', app.logout);
    };

    this.logout = function () {
        $.when($.ajax({url: '/api/auth/logout/', type: 'GET', dataType: 'json'}))
            .then(function (data/*, textStatus, jqXHR*/) {
                if (data.status === true) {
                    location.reload();
                }
            });
    };

    this.getSoapOperations = function () {
        let listwrp = $('#list-wrapper');
        $(listwrp).removeClass('auth-message-alert');
        $(listwrp).html('Please, wait...');
        $.when($.ajax({url: '/api/soap/operations/', type: 'GET', dataType: 'json'}))
            .then(function (data/*, textStatus, jqXHR*/) {
                if (data.status === true) {
                    let html = list(data.response);
                    $(listwrp).html(html);
                }
            });
    };

    this.getSoapTypes = function() {
        let listwrp = $('#list-wrapper');
        $(listwrp).removeClass('auth-message-alert');
        $(listwrp).html('Please, wait...');
        $.when($.ajax({url: '/api/soap/types/', type: 'GET', dataType: 'json'}))
            .then(function (data/*, textStatus, jqXHR*/) {
                if (data.status === true) {
                    let html = list(data.response);
                    $(listwrp).html(html);
                }
            });
    };


}

require('./footerText')();

let main = new Main();
main.init();