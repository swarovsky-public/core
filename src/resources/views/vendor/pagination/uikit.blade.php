@if ($paginator->hasPages())
    <ul class="uk-pagination">
        <li style="{{ $paginator->onFirstPage()? 'display:none;' : '' }}">
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">
                <span uk-pagination-previous class="uk-margin-small-right"></span> Previous
            </a>
        </li>
        <li class="uk-margin-auto-left" style="{{ $paginator->hasMorePages()? '' : 'display:none;' }}">
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">
                Next <span uk-pagination-next class="uk-margin-small-left"></span>
            </a>
        </li>
    </ul>
@endif

