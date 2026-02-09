<?php

namespace App\Jobs;

use App\Events\ZipGeradoEvent;
use App\Models\Curso;
use App\Models\Inscricao;
use Illuminate\Support\Facades\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GerarZipTodosDocumentosCandidatosJob
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $curso_id;
    protected $chamada_id;

    public function __construct($curso_id, $chamada_id)
    {
        $this->curso_id = $curso_id;
        $this->chamada_id = $chamada_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $curso = Curso::find($this->curso_id);
        $inscricoes = Inscricao::where([['curso_id', $curso->id], ['chamada_id', $this->chamada_id]])->get();

        $filename = 'Documentos dos Candidatos(' . $curso->nome . ' - ' . $curso->getTurno() . ').zip';
        $zip = new ZipArchive();
        $zip->open(Storage::path($filename), ZipArchive::CREATE);


        $temArquivo = false;

        foreach ($inscricoes as $inscricao) {
            if ($inscricao->arquivos->isNotEmpty()) {
                $temArquivo = true;
                $nomeCandidato = $inscricao->candidato->no_inscrito . ' - ' . $inscricao->co_inscricao_enem;

                $zip->addEmptyDir($nomeCandidato);

                $files = File::files(Storage::path("documentos/inscricaos/$inscricao->id"));

                foreach ($files as $file) {
                    $relativeName = basename($file);
                    $zip->addFile($file, "$nomeCandidato/$relativeName");
                }
            }
        }

        $zip->close();

        if (!$temArquivo) {
            Storage::disk('local')->delete($filename);

            // event(new ZipGeradoEvent(
            //     $this->user_id,
            //     null
            // ));

            return;
        }

        return $filename;
        // event(new ZipGeradoEvent($this->user_id, $filename));
    }
}
