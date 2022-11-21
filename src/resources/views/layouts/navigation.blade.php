<div class="flex bg-white flex-col md:w-3/12  lg:w-1/4 xl:w-1/5">
    <div class="flex flex-col justify-between mt-6">
        <aside class="text-xs lg:text-sm">
            <ul>
                <li>
                    <x-nav-link :href="route('content.home')" :active="request()->routeIs('content.home')">
                        <i class="fa fa-house"></i>
                        <span class="mx-4 font-medium">Menu Principal</span>
                    </x-nav-link>
                </li>
                @foreach ($menus as $menu)
                    <li class="dropdown relative">
                        <x-nav-link :href="route('content.show', ['category' => $menu->slug])">
                            {!! $menu->icon !!}
                            <span class="mx-4 font-medium w-full">
                                {{ $menu->name }}

                                @if ($menu->slug == 'feed-de-noticias' and sizeof($popups) > 0)
                                    <span
                                        class="inline-flex justify-center items-center text-xs rounded-full bg-red-semparar h-4 w-4 text-white">
                                        <span>{{ sizeof($popups) }}</span>
                                    </span>
                                @endif
                            </span>
                        </x-nav-link>
                        <x-nav-dropdown :category_children="$menu->children->where('status', '1')->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)" :posts="$menu->posts
                            ->where('status_id', '1')
                            ->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE)" />
                    </li>
                @endforeach
                @canany(['categories.index', 'posts.index', 'roles.index', 'questionnaires.index', 'slideshow.index',
                    'users.index', 'approvals.index', 'reports.loginLogout.index', 'reports.posts.index',
                    'reports.users.index', 'reports.billing.index', 'reports.questionnaires.index'])
                    <li class="dropdown relative">
                        <x-nav-link>
                            <i class="fa fa-cog"></i>
                            <span class="mx-4 font-medium">Admin</span>
                        </x-nav-link>
                        <ul class="dropdown-menu bg-white ml-12 text-gray-semparar shadow-md">
                           
                            @can('roles.index')
                                <li class="dropdown relative w-44">
                                    <x-nav-link href="{{ route('roles.index') }}" class="p-2 hover:text-red-semparar">
                                        Perfis
                                    </x-nav-link>
                                </li>
                            @endcan
                            @can('users.index')
                                <li class="dropdown relative w-44">
                                    <x-nav-link href="{{ route('users.index') }}" class="p-2 hover:text-red-semparar">
                                        Usuários
                                    </x-nav-link>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
            </ul>
        </aside>
    </div>
</div>
