<?php

namespace App\Modules\File\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'files';

    CONST TABLE='files';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre_original', 'tamanio', 'extension', 'tipo', 'hash', 'ruta_url','status_id','user_id'];

    protected $appends = ['url_s3'];

    protected $hidden = ['created_at','updated_at','ruta_url'];

    public function user()
    {
        return $this->belongsTo('App\Modules\Auth\Models\User');
    }
    public function status()
    {
        return $this->belongsTo('App\Modules\Api\Models\Status');
    }

    public function getUrlS3Attribute()
    {
        return asset('storage/' . $this->attributes['ruta_url']);
    }

}
