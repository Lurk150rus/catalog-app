<ul>
    @foreach($groups as $group)
        <li>
            <a href="{{ route('catalog.index', array_merge(request()->except('page'), ['group' => $group->id])) }}"
               style="{{ isset($groupId) && $groupId == $group->id ? 'font-weight:bold;' : '' }}">
                {{ $group->name }} ({{ $group->totalProductCount() }})
            </a>

            {{-- Если текущая группа есть в списке раскрытых — показываем её детей --}}
            @if($group->children->isNotEmpty() && isset($expandedGroupIds) && in_array($group->id, $expandedGroupIds))
                @include('partials.group-tree', [
                    'groups' => $group->children,
                    'groupId' => $groupId ?? null,
                    'expandedGroupIds' => $expandedGroupIds ?? []
                ])
            @endif
        </li>
    @endforeach
</ul>
