<?php

namespace App\Modules\File\Models;

use App\Modules\TipoArchivoEspacioTrabajo\Models\TipoArchivoEspacioTrabajo;
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



    protected $hidden = ['created_at','updated_at','ruta_url'];

    public function user()
    {
        return $this->belongsTo('App\Modules\Auth\Models\User');
    }
    public function status()
    {
        return $this->belongsTo('App\Modules\Api\Models\Status');
    }

    public function espaciosTrabajos()
    {
        return $this->belongsToMany('App\Modules\EspacioTrabajo\Models\EspacioTrabajo', 'espacio_trabajos_files', 'file_id', 'espacio_trabajo_id')
            ->withPivot('espacio_trabajo_id','file_id','user_id','tipo_archivo_espacio_trabajo_id','created_at','updated_at');

    }

    public function tipoArchivoEspacioTrabajo()
    {
        return $this->hasOne(TipoArchivoEspacioTrabajo::class, 'id', 'tipo_archivo_espacio_trabajo_id');
    }
}
