<div class="row">

<!-- Profile Info and Notifications -->
<div class="col-md-6 col-sm-8 clearfix">

<ul class="user-info pull-left pull-none-xsm">

    @if(Sentry::check())
    <!-- Profile Info -->
    <li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->


        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            @if($currentUser->profile)
                <img src="{{ $currentUser->profile->avatar->url('thumb') }}" alt="" class="img-circle" width="44" />
            @endif
            {{ $currentUser->name() }}

        </a>


        <ul class="dropdown-menu">

            <!-- Reverse Caret -->
            <li class="caret"></li>

            <!-- Profile sub-links -->
            <li>
                <a href="{{ route('profiles.show', $currentUser->id ) }}">
                    <i class="entypo-user"></i>
                    My Profile
                </a>
            </li>
        </ul>
    </li>

    <li id="search"><!-- add class "search-input-collapsed" to auto collapse search input -->
    <div class="searchbox">
        {{ Form::open(['route'=>'portal.orders.search', 'method'=>'GET', 'class' => 'form-inline', 'role' => 'form' ]) }}
            <div class="form-group">
                <input type="text" name="s" class="form-control input-lg" id="exampleInputEmail2" placeholder="Search web orders">
                <span class="search-button">
                    <button type="submit" class="btn btn-primary btn-lg btn-searchbox">Search</button>
                </span>
            </div>
        {{ Form::close() }}
    </div>
    </li>


    @endif

</ul>

</div>


<!-- Raw Links -->
<div class="col-md-6 col-sm-4 clearfix hidden-xs">

    <ul class="list-inline links-list pull-right">
        @if($currentUser->hasAccess('company-settings'))
            <li>
                <a href="{{ route('company-settings.edit') }}">
                    Company Settings <i class="entypo-cog right"></i>
                </a>
            </li>
        @endif
        <li>
            <a href="{{ route('logout_path') }}">
                Log Out <i class="entypo-logout right"></i>
            </a>
        </li>
    </ul>

</div>

</div>

<hr />