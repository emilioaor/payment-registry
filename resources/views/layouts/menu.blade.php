<custom-menu
    :menu = "{{ json_encode($menu) }}"
    :user = "{{ json_encode(Auth::user()) }}"
></custom-menu>
