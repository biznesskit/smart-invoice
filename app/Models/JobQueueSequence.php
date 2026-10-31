<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class JobQueueSequence extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $table = 'job_queue_sequences'; // Explicitly define the table name


    /**
     * Get the model that is assigned to this job queue sequencer.
     */
    public function queueable(): MorphTo
    {
        return $this->morphTo();
    }

}
