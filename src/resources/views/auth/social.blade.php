<div class="uk-margin uk-grid-match uk-child-width-1-2@s" uk-grid id="social-view">

    @if(filled(env('FACEBOOK_APP_ID')))
    <div>
        <a href="{{url('/redirect/google')}}"
           title="Login with Google"
           class="uk-button uk-button-default uk-button-large uk-text-right" style="padding-right: 15px;">
            <div class="google-button"></div>
            Google
        </a>
    </div>
    @endif
        @if(filled(env('GOOGLE_CLIENT_ID')))
    <div>
        <a href="{{url('/redirect/facebook')}}"
           title="Login with Facebook"
           class="uk-button uk-button-large uk-button-default uk-text-right" style="padding-right: 15px;">
            <div class="facebook-button"></div>
            Facebook
        </a>
    </div>
            @endif

</div>
@if(filled(env('COMING_SOON')))
<div class="uk-margin uk-grid-match uk-child-width-1-2@s uk-grid" uk-grid="">

    <div class="uk-first-column">
        <a href="https://www.queendomofhera.com/redirect/twitter" title="Login with Twitter" class="uk-button uk-button-default uk-button-large uk-text-right" style="padding-right: 15px;" disabled="">
            <div class="twitter-button"></div>
            Twitter
            <span style="position: absolute;font-size: 10px;left: 0;right: 5px;bottom: 7px;line-height: 0;padding: 0;margin: 0;color: #737373;">Coming soon</span>
        </a>
    </div>
    <div>
        <a href="https://www.queendomofhera.com/redirect/epic" title="Login with Epic games" class="uk-button uk-button-large uk-button-default uk-text-right" style="padding-right: 15px;" disabled="">
            <div class="epic-button"></div>
            Epic Games
            <span style="position: absolute;font-size: 10px;left: 0;right: 5px;bottom: 7px;line-height: 0;padding: 0;margin: 0;color: #737373;">Coming soon</span>
        </a>
    </div>

</div>
@endif
