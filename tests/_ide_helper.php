<?php

/**
 * Pest IDE Helper for better autocompletion
 * This file helps IDEs understand Pest's magic binding
 * 
 * @mixin \Tests\TestCase
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */
class PestTestContext extends Tests\TestCase
{
    // This class is never actually used, it's just for IDE assistance
}
