@forelse ($submenus  as $menu )
    <li class="" id="{{ str_replace(' ','',$menu->submenu ) }}"> {{ $menu->submenu }}</li>
    {{-- <li class="">Profile Preferance Setup</li>
    <li class="">Identification Setup</li> --}}
@empty

@endforelse
