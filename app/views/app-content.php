<div class="content">
    <!-- Navigation -->
    <div class="top-bar" id="realEstateMenu">
        <div class="top-bar-left">
            <ul class="menu">
                <li class="menu-text">{{site_name}}</li>
            </ul>
        </div>
        <div class="top-bar-right">
            <ul class="menu">
                <li id="help-button"><a class="button small button-radius">Help</a></li>
                <li id="logout-button" class="logout-button"><a class="button small alert button-radius">Logout</a>
            </ul>
        </div>
    </div>
    <!-- /Navigation -->

    <br>
    <article class="grid-container">
        <div class="grid-x grid-margin-x">

            <div class="medium-7 large-6 cell">
                <h1>{{site_name}}</h1>
                <p class="subheader">
                    A page requesting the train's route by train number, departure / arrival stations and date.
                </p>
                <p class="subheader">
                    The server part is implemented using:<br/>
                    <span class="stack-line">PHP (MVC, SOAP, API, Classloader)</span>
                </p>
                <p class="subheader">
                    The client part is implemented using:<br/>
                    <span class="stack-line">Webpack, SASS, ES6, Foundation, JQuery, Handlebars</span>
                </p>
                <button id="operations-button" class="button small">Available operations</button>
                <button id="types-button" class="button small">Available types</button>
            </div>


            <div class="medium-5 large-6 cell">
                <div id="form-wrapper">
                    Please, wait...
                </div>
            </div>

        </div>

        <div class="">
            <hr>
        </div>

        <div id="list-wrapper">
            <!--route list table-->
        </div>

    </article>
</div>

<footer class="footer">

    <div class="grid-x">

        <div class="medium-12 cell">
            <ul class="menu align-center">
                <li id="footer-text" class="menu-text">Copyright 2018</li>
            </ul>
        </div>
    </div>

</footer>