<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Simple Bank</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="/people">
        	@if(Request::path() == 'people')
        	<strong>People</strong>
        	@else
        	People
        	@endif
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/accounts">
        	@if(Request::path() == 'accounts')
        	<strong>Accounts</strong>
        	@else
        	Accounts
        	@endif
    	</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/statements">
        	@if(Request::path() == 'statements')
        	<strong>Transactions</strong>
        	@else
        	Transactions
        	@endif
        </a>
      </li>
    </ul>
  </div>
</nav>