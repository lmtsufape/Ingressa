<?php

namespace App\Http\Livewire;

use App\Models\Arquivo;
use App\Models\Inscricao;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\FileUploadConfiguration;
use Livewire\WithFileUploads;

class EnviarDocumentos extends Component
{
    use WithFileUploads;

    public $documentos;
    public $declaracoes;
    public $arquivos;
    public $inscricao;
    protected $validationAttributes = [
        'arquivos.certificado_conclusao' => 'certificado de conclusão',
        'arquivos.historico' => 'histórico escolar',
        'arquivos.nascimento_ou_casamento' => 'registro de nascimento ou casamento',
        'arquivos.cpf' => 'CPF',
        'arquivos.rg' => 'RG',
        'arquivos.quitacao_eleitoral' => 'comprovante de quitação eleitoral',
        'arquivos.quitacao_militar' => 'comprovante de quitação militar',
        'arquivos.foto' => 'foto',
        'arquivos.fotografia' => 'fotografia',
        'arquivos.rani' => 'RANI',
        'arquivos.heteroidentificacao' => 'vídeo para heteroidentificação',
        'arquivos.declaracao_veracidade' => 'declaração de veracidade',
        'arquivos.declaracao_cotista' => 'autodeclaração',
        'arquivos.comprovante_renda' => 'comprovante de renda',
        'arquivos.laudo_medico' => 'laudo médico',
    ];
    public function mount($documentos)
    {
        foreach ($documentos as $documento) {
            $this->arquivos[$documento] = null;
            switch ($documento) {
                case 'historico':
                case 'nascimento_ou_casamento':
                case 'quitacao_militar':
                    $this->declaracoes[$documento] = null;
                    break;
                case 'quitacao_eleitoral':
                    $this->declaracoes[$documento] = null;
                    $this->declaracoes['votoFacultativo'] = null;
                    break;
            default:
                    break;
            }
        }
    }

    public function rules()
    {
        $rules = [];
        foreach ($this->documentos as $documento) {
            $rules['arquivos.'.$documento] = ['required', 'mimes:pdf', 'max:2048'];
            switch ($documento) {
                case 'heteroidentificacao':
                    $rules['arquivos.'.$documento] = ['required', 'file',  'mimes:mp4', 'max:51200'];
                    break;
                case 'historico':
                case 'nascimento_ou_casamento':
                case 'quitacao_militar':
                    if($this->declaracoes[$documento]) {
                        $rules['arquivos.'.$documento] = 'required_unless:declaracoes.'.$documento.',true';
                    }
                    break;
                case 'quitacao_eleitoral':
                    $rules['arquivos.'.$documento] = 'required_without_all:declaracoes.quitacao_eleitoral,votoFacultativo';
                    break;
            default:
                    break;
            }
        }
        return $rules;
    }

    public function render()
    {
        return view('livewire.enviar-documentos');
    }

    public function submit()
    {
        $this->validate();
        $this->inscricao->status = Inscricao::STATUS_ENUM['documentos_enviados'];
        $this->inscricao->save();
        return redirect(route('inscricaos.index'))->with(['success' => 'Documentação enviada com sucesso. Aguarde o resultado da avaliação dos documentos.']);
    }

    public function updated($documento, $value)
    {
        if (explode('.', $documento)[0] == 'arquivos' && $this->inscricao->isDocumentosRequeridos()) {
            $this->validateOnly($documento);
            $documento = explode('.', $documento)[1];
            $path = 'documentos/inscricaos/'. $this->inscricao->id . '/';
            $nome = $documento . '.' . $value->getClientOriginalExtension();
            $arquivo = Arquivo::where([['inscricao_id', $this->inscricao->id], ['nome', $documento]])->first();
            if($arquivo != null){
                if (Storage::exists($arquivo->caminho)) {
                    Storage::delete($arquivo->caminho);
                }
                $value->storeAs('public/'.$path, $nome);
            }else{
                $value->storeAs('public/'.$path, $nome);
                Arquivo::create([
                    'inscricao_id' => $this->inscricao->id,
                    'caminho' => $path.$nome,
                    'nome' => $documento,
                ]);
            }
        }
    }

    protected function cleanupOldUploads()
    {
        if (FileUploadConfiguration::isUsingS3()) return;

        $storage = FileUploadConfiguration::storage();

        foreach ($storage->allFiles(FileUploadConfiguration::path()) as $filePathname) {
            // On busy websites, this cleanup code can run in multiple threads causing part of the output
            // of allFiles() to have already been deleted by another thread.
            if (! $storage->exists($filePathname)) continue;

            $yesterdaysStamp = now()->subMinutes(20)->timestamp;
            if ($yesterdaysStamp > $storage->lastModified($filePathname)) {
                $storage->delete($filePathname);
            }
        }
    }
}
