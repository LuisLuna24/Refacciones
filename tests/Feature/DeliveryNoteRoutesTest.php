<?php

use Illuminate\Support\Facades\Route;

test('the delivery note pdf route is registered', function () {
    expect(Route::has('admin.delivery_notes.pdf'))->toBeTrue();
});
