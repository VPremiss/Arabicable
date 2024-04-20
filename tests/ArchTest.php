<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'var_dump', 'Illuminate\Support\Facades\Log', 'echo'])
    ->each->not->toBeUsed();
