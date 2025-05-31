<?php

namespace App\Modules\Auth\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreated
{
    use Dispatchable, SerializesModels;

    public $new_user;
    public $pass;
    /**
     * Create a new event instance.
     *
     * @param  mixed  $datos
     * @return void
     */
    public function __construct($new_user,$pass)
    {
        $this->new_user = $new_user;
        $this->pass = $pass;
    }

}
