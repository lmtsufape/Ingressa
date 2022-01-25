<?php

namespace App\Http\Livewire;

use App\Http\Controllers\InscricaoController;
use App\Models\Arquivo;
use App\Models\Avaliacao;
use App\Models\Inscricao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\FileUploadConfiguration;
use Livewire\WithFileUploads;
use App\Notifications\ComprovanteEnvioDocumentosNotification;


class EnviarDocumentos extends Component
{
    use LivewireAlert;
    use AuthorizesRequests;
    use WithFileUploads;

    public $documentos;
    public $declaracoes;
    public $arquivos;
    public $inscricao;
    protected $validationAttributes = [];
    protected $messages = [
        'arquivos.historico.required_without_all' => 'O campo :attribute é obrigatório quando não marcar o termo de compromisso para entregar o documento na primeira semana de aula.',
        'arquivos.nascimento_ou_casamento.required_without_all' => 'O campo :attribute é obrigatório quando não marcar o termo de compromisso para entregar o documento na primeira semana de aula.',
        'arquivos.quitacao_militar.required_without_all' => 'O campo :attribute é obrigatório quando não marcar o termo de compromisso para entregar o documento na primeira semana de aula.',
        'arquivos.quitacao_eleitoral.required_without_all' => 'O campo :attribute é obrigatório quando não marcar o termo de compromisso para entregar o documento na primeira semana de aula.',
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
                    break;
            default:
                    break;
            }
        }
    }

    public function attributes()
    {
        foreach ($this->documentos as $documento) {
            $this->validationAttributes['arquivos.'.$documento] = InscricaoController::getNome($documento);
        }
    }

    /**
     * Função para verificar se o candidato já enviou o arquivo e se o documento foi recusado.
     * Retorna true se o documento foi enviado mas ainda não tem avaliação ou se o documento foi enviado e aceito.
     * Retorna false se o documento foi enviado e recusado.
     */
    private function arquivoEnviado($documento)
    {
        return !is_null($this->inscricao->arquivo($documento))
            && (is_null($this->inscricao->arquivo($documento)->avaliacao)
            || !$this->inscricao->arquivo($documento)->avaliacao->isRecusado());
    }

    public function rulePdf($documento)
    {
        if ($this->arquivoEnviado($documento)) {
            return ['nullable', 'file', 'mimes:pdf', 'max:2048'];
        } else {
            return ['required', 'file', 'mimes:pdf', 'max:2048'];
        }
    }

    public function rulePdfWithoutAll($documento, $all)
    {
        $nomes = implode(',', $all);
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'mimes:pdf', 'max:2048'];
        } else {
            return ['required_without_all:'.$nomes, 'nullable', 'file', 'mimes:pdf', 'max:2048'];
        }
    }

    public function rulePdfIf($documento, $nome)
    {
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'file', 'mimes:pdf', 'max:2048'];
        } else {
            return ['required_if:'.$nome.',true', 'nullable', 'file', 'mimes:pdf', 'max:2048'];
        }
    }

    public function ruleVideo($documento)
    {
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'file', 'mimes:mp4', 'max:65536'];
        } else {
            return ['required', 'file', 'mimes:mp4', 'max:65536'];
        }
    }

    public function ruleVideoIf($documento, $nome)
    {
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'file', 'mimes:mp4', 'max:65536'];
        } else {
            return ['required_if:'.$nome.',true', 'nullable', 'file', 'mimes:mp4', 'max:65536'];
        }
    }

    public function ruleImage($documento)
    {
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'image', 'max:10240'];
        } else {
            return ['required', 'image', 'max:10240'];
        }
    }

    public function ruleImageWithoutAll($documento, $all)
    {
        $nomes = implode(',', $all);
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'image', 'max:10240'];
        } else {
            return ['required_without_all:'.$nomes, 'image', 'max:10240'];
        }
    }

    public function ruleImageIf($documento, $nome)
    {
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'image', 'max:10240'];
        } else {
            return ['required_if:'.$nome.',true', 'nullable', 'image', 'max:10240'];
        }
    }

    public function rules()
    {
        $rules = [];
        foreach ($this->documentos as $documento) {
            if($documento == 'certificado_conclusao') {
                $rules['arquivos.'.$documento] = $this->rulePdf($documento);
            } elseif($documento == 'historico') {
                $all = ['declaracoes.historico'];
                $rules['arquivos.'.$documento] = $this->rulePdfWithoutAll($documento, $all);
            } elseif($documento == 'nascimento_ou_casamento') {
                $all = ['declaracoes.nascimento_ou_casamento'];
                $rules['arquivos.'.$documento] = $this->rulePdfWithoutAll($documento, $all);
            } elseif($documento == 'cpf') {
                $rules['arquivos.'.$documento] = $this->rulePdf($documento);
            } elseif($documento == 'rg') {
                $rules['arquivos.'.$documento] = $this->rulePdf($documento);
            } elseif($documento == 'quitacao_eleitoral') {
                $all = ['declaracoes.quitacao_eleitoral'];
                $rules['arquivos.'.$documento] = $this->rulePdfWithoutAll($documento, $all);
            } elseif($documento == 'quitacao_militar') {
                $all = ['declaracoes.quitacao_militar'];
                $rules['arquivos.'.$documento] = $this->rulePdfWithoutAll($documento, $all);
            } elseif($documento == 'foto') {
                $rules['arquivos.'.$documento] = $this->ruleImage($documento);
            } elseif($documento == 'comprovante_renda') {
                $rules['arquivos.'.$documento] = $this->rulePdf($documento);
            } elseif($documento == 'laudo_medico') {
                $rules['arquivos.'.$documento] = $this->rulePdf($documento);
            } elseif($documento == 'declaracao_veracidade') {
                $rules['arquivos.'.$documento] = $this->rulePdf($documento);
            } elseif($documento == 'rani') {
                $rules['arquivos.'.$documento] = $this->rulePdf($documento);
            } elseif($documento == 'heteroidentificacao') {
                $rules['arquivos.'.$documento] = $this->ruleVideo($documento);
            } elseif($documento == 'fotografia') {
                $rules['arquivos.'.$documento] = $this->ruleImage($documento);
            } elseif($documento == 'declaracao_cotista') {
                $rules['arquivos.'.$documento] = $this->rulePdf($documento);
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
        $this->rules();
        $this->attributes();
        $this->validate();
        $this->inscricao->status = Inscricao::STATUS_ENUM['documentos_enviados'];
        $this->inscricao->save();

        Notification::send(auth()->user(), new ComprovanteEnvioDocumentosNotification('Comprovante de envio', $this->inscricao));

        return redirect(route('inscricaos.index'))->with(['success' => 'Documentação enviada com sucesso. Aguarde o resultado da avaliação dos documentos.']);
    }

    public function updated($documento, $value)
    {
        $this->attributes();
        $this->authorize('dataEnvio', $this->inscricao->chamada);
        if (explode('.', $documento)[0] == 'arquivos' && ($this->inscricao->isDocumentosRequeridos() || $this->inscricao->isArquivoRecusadoOuReenviado(explode('.', $documento)[1]))) {
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
                if($arquivo->avaliacao != null)
                {
                    $avaliacao = $arquivo->avaliacao;
                    $avaliacao->avaliacao = Avaliacao::AVALIACAO_ENUM['reenviado'];
                    $avaliacao->save();
                }
            }else{
                $value->storeAs('public/'.$path, $nome);
                Arquivo::create([
                    'inscricao_id' => $this->inscricao->id,
                    'caminho' => $path.$nome,
                    'nome' => $documento,
                ]);
            }
            $this->alert('success', 'Arquivo enviado com sucesso!', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
                'timerProgressBar' => true,
                'width' => '400',
                ]);
        }
    }

    public function baixar($documento)
    {
        return response()->download('storage/' . $this->inscricao->arquivo($documento)->caminho);
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
