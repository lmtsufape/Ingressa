<?php

namespace App\Livewire;

use App\Http\Controllers\InscricaoController;
use App\Models\Arquivo;
use App\Models\Avaliacao;
use App\Models\Inscricao;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\WithFileUploads;
use App\Notifications\ComprovanteEnvioDocumentosNotification;
use App\Rules\ArquivoEnviado;
use App\Rules\ArquivoNaoObrigatorioEnviado;
use Illuminate\Validation\Validator;

class EnviarDocumentos extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public $documentos;
    public $declaracoes;
    public $arquivos;
    public $inscricao;
    public $nomes;
    public $termos = ['prouni' => null, 'vinculo' => null, 'confirmacaovinculo' => null];
    protected $validationAttributes = [];
    protected $messages = [
        'termos.vinculo.required' => 'O termo acima é obrigatório.',
        'termos.prouni.required' => 'O termo acima é obrigatório.',
        'termos.confirmacaovinculo.required' => 'O termo acima é obrigatório.',
        'termos.vinculo.accepted' => 'O termo acima é obrigatório.',
        'termos.prouni.accepted' => 'O termo acima é obrigatório.',
        'termos.confirmacaovinculo.accepted' => 'O termo acima é obrigatório.',
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
            $this->nomes[$documento] = InscricaoController::getNome($documento);
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

    public function rulePdf($documento, $tamanho = 5120)
    {
        if ($this->arquivoEnviado($documento)) {
            return ['nullable', 'file', 'mimes:pdf', 'max:'.$tamanho];
        } else {
            return ['required', 'file', 'mimes:pdf', 'max:'.$tamanho];
        }
    }

    public function rulePdfWithoutAll($documento, $all)
    {
        $nomes = implode(',', $all);
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'mimes:pdf', 'max:5120'];
        } else {
            return ['required_without_all:'.$nomes, 'nullable', 'file', 'mimes:pdf', 'max:5120'];
        }
    }

    public function rulePdfIf($documento, $nome)
    {
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'file', 'mimes:pdf', 'max:5120'];
        } else {
            return ['required_if:'.$nome.',true', 'nullable', 'file', 'mimes:pdf', 'max:5120'];
        }
    }

    public function ruleVideo($documento)
    {
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'file', 'mimes:mp4,avi,wmv,mjpeg', 'max:122880'];
        } else {
            return ['required', 'file', 'mimes:mp4,avi,wmv,mjpeg', 'max:122880'];
        }
    }

    public function ruleVideoIf($documento, $nome)
    {
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'file', 'mimes:mp4,avi,wmv,mjpeg', 'max:122880'];
        } else {
            return ['required_if:'.$nome.',true', 'nullable', 'file', 'mimes:mp4,avi,wmv,mjpeg', 'max:122880'];
        }
    }

    public function ruleImage($documento)
    {
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'image', 'max:92160'];
        } else {
            return ['required', 'image', 'max:92160'];
        }
    }

    public function ruleImageWithoutAll($documento, $all)
    {
        $nomes = implode(',', $all);
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'image', 'max:92160'];
        } else {
            return ['required_without_all:'.$nomes, 'image', 'max:92160'];
        }
    }

    public function ruleImageIf($documento, $nome)
    {
        if($this->arquivoEnviado($documento)) {
            return ['nullable', 'image', 'max:92160'];
        } else {
            return ['required_if:'.$nome.',true', 'nullable', 'image', 'max:92160'];
        }
    }

    protected function rules()
    {
        $rules = [];
        $rules['termos.vinculo'] = ['required', 'accepted'];
        $rules['termos.prouni'] = ['required', 'accepted'];
        $rules['termos.confirmacaovinculo'] = ['required', 'accepted'];
        if($this->documentos->contains('certificado_conclusao')) {
            $rules['arquivos.certificado_conclusao'] = $this->rulePdf('certificado_conclusao');
        }
        if($this->documentos->contains('historico')) {
            $all = ['declaracoes.historico'];
            $rules['arquivos.historico'] = $this->rulePdfWithoutAll('historico', $all);
        }
        if($this->documentos->contains('nascimento_ou_casamento')) {
            $all = ['declaracoes.nascimento_ou_casamento'];
            $rules['arquivos.nascimento_ou_casamento'] = $this->rulePdfWithoutAll('nascimento_ou_casamento', $all);
        }
        if($this->documentos->contains('cpf')) {
            $rules['arquivos.cpf'] = $this->rulePdf('cpf');
        }
        if($this->documentos->contains('rg')) {
            $rules['arquivos.rg'] = $this->rulePdf('rg');
        }
        if($this->documentos->contains('quitacao_eleitoral')) {
            $all = ['declaracoes.quitacao_eleitoral'];
            $rules['arquivos.quitacao_eleitoral'] = $this->rulePdfWithoutAll('quitacao_eleitoral', $all);
        }
        if($this->documentos->contains('quitacao_militar')) {
            $all = ['declaracoes.quitacao_militar'];
            $rules['arquivos.quitacao_militar'] = $this->rulePdfWithoutAll('quitacao_militar', $all);
        }
        if($this->documentos->contains('foto')) {
            $rules['arquivos.foto'] = $this->ruleImage('foto');
        }
        if($this->documentos->contains('comprovante_renda')) {
            $rules['arquivos.comprovante_renda'] = $this->rulePdf('comprovante_renda', 65536);
        }
        if($this->documentos->contains('laudo_medico')) {
            $rules['arquivos.laudo_medico'] = $this->rulePdf('laudo_medico', 65536);
        }
        if($this->documentos->contains('declaracao_veracidade')) {
            $rules['arquivos.declaracao_veracidade'] = $this->rulePdf('declaracao_veracidade');
        }
        if($this->documentos->contains('rani')) {
            $rules['arquivos.rani'] = $this->rulePdf('rani');
        }
        if($this->documentos->contains('declaracao_quilombola')) {
            $rules['arquivos.declaracao_quilombola'] = $this->rulePdf('declaracao_quilombola');
        }
        if($this->documentos->contains('heteroidentificacao')) {
            $rules['arquivos.heteroidentificacao'] = $this->ruleVideo('heteroidentificacao');
        }
        if($this->documentos->contains('fotografia')) {
            $rules['arquivos.fotografia'] = $this->ruleImage('fotografia');
        }
        if($this->documentos->contains('declaracao_cotista')) {
            $rules['arquivos.declaracao_cotista'] = $this->rulePdf('declaracao_cotista');
        }
        return $rules;
    }

    public function render()
    {
        return view('livewire.enviar-documentos');
    }

    private function rulesSubmit()
    {
        $rules = [];
        $rules['termos.vinculo'] = ['required', 'accepted'];
        $rules['termos.prouni'] = ['required', 'accepted'];
        $rules['termos.confirmacaovinculo'] = ['required', 'accepted'];
        foreach ($this->documentos as $documento) {
            if ($this->arquivoNaoObrigatorio($documento)) {
                $rules['arquivos.'.$documento] = [new ArquivoNaoObrigatorioEnviado($this->inscricao, $documento, $this->declaracoes[$documento])];
            } else {
                $rules['arquivos.'.$documento] = [new ArquivoEnviado($this->inscricao, $documento)];
            }
        }
        return $rules;
    }

    public function submit()
    {
        $this->rules();
        $this->attributes();
        $this->withValidator(function (Validator $validator) {
            if ($validator->fails()) {
                $this->dispatch('swal:fire', [
                    'icon' => 'error',
                    'title' => 'Erro ao enviar os arquivos, verifique os campos inválidos!'
                ]);
            }
        })->validate($this->rulesSubmit());
        if ($this->inscricao->isDocumentoAceitosComPendencias()) {
            foreach ($this->documentos as $documento) {
                if ($this->inscricao->isArquivoEnviado($documento) && !$this->inscricao->isArquivoAvaliado($documento))
                    $this->inscricao->status = Inscricao::STATUS_ENUM['documentos_enviados'];
            }
        } else {
            $this->inscricao->status = Inscricao::STATUS_ENUM['documentos_enviados'];
        }
        $this->inscricao->save();

        Notification::send(auth()->user(), new ComprovanteEnvioDocumentosNotification('Comprovante de envio', $this->inscricao, $this->documentos));

        return redirect(route('inscricaos.index'))->with(['success' => 'Documentação enviada com sucesso. Aguarde o resultado da avaliação dos documentos.']);
    }

    public function updated($documento, $value)
    {
        $this->attributes();
        if(auth()->user()->role != User::ROLE_ENUM['admin']){
            $this->authorize('dataEnvio', $this->inscricao->chamada);
        }
        if (
            explode('.', $documento)[0] == 'arquivos'
            && is_null($this->inscricao->retificacao)
            && ($this->inscricao->isDocumentosRequeridos()
            || $this->inscricao->isArquivoRecusadoOuReenviado(explode('.', $documento)[1])
            || $this->inscricao->isDocumentoAceitosComPendencias()
            || $this->arquivoNaoObrigatorio(explode('.', $documento)[1])
            || auth()->user()->role == User::ROLE_ENUM['admin'])
        ) {
            $this->withValidator(function (Validator $validator) {
                if ($validator->fails()) {
                    $this->dispatch('swal:fire', [
                        'icon' => 'error',
                        'title' => 'Erro ao enviar o arquivo, verifique o campo inválido!'
                    ]);
                }
            })->validateOnly($documento);
            $documento = explode('.', $documento)[1];
            $path = 'documentos/inscricaos/'. $this->inscricao->id . '/';
            $nome = $documento . '.' . $value->getClientOriginalExtension();
            $arquivo = Arquivo::where([['inscricao_id', $this->inscricao->id], ['nome', $documento]])->first();
            if($arquivo != null){
                if (Storage::exists($arquivo->caminho)) {
                    Storage::delete($arquivo->caminho);
                }
                $value->storeAs($path, $nome);
                $arquivo->caminho = $path.$nome;
                $arquivo->save();
                if($arquivo->avaliacao != null)
                {
                    $avaliacao = $arquivo->avaliacao;
                    $avaliacao->avaliacao = Avaliacao::AVALIACAO_ENUM['reenviado'];
                    $avaliacao->save();
                }
            }else{
                $value->storeAs($path, $nome);
                Arquivo::create([
                    'inscricao_id' => $this->inscricao->id,
                    'caminho' => $path.$nome,
                    'nome' => $documento,
                ]);
            }
            $this->dispatch('swal:fire', [
                'icon' => 'success',
                'title' => 'Arquivo enviado com sucesso!'
            ]);
        }
    }

    private function arquivoNaoObrigatorio($documento)
    {
        return in_array($documento, ['historico', 'quitacao_militar', 'nascimento_ou_casamento', 'quitacao_eleitoral']);
    }

    public function baixar($documento)
    {
        return response()->download(storage_path('app/'.$this->inscricao->arquivo($documento)->caminho));
    }

    public function apagar($documento)
    {
        if(auth()->user()->role != User::ROLE_ENUM['admin']){
            $this->authorize('periodoEnvio', $this->inscricao->chamada);
            if ($this->inscricao->arquivo($documento) == null)
            if($this->inscricao->isDocumentosRequeridos() || ($this->inscricao->isArquivoRecusadoOuReenviado($documento) && $this->inscricao->isDocumentosInvalidados()))
                {
                    $this->dispatch('swal:fire', [
                        'icon' => 'error',
                        'title' => 'Não é possível deletar este arquivo!'
                    ]);
                    return;
                }
            $arquivo = $this->inscricao->arquivo($documento);
            if (Storage::exists($arquivo->caminho)) {
                Storage::delete($arquivo->caminho);
            }
            $arquivo->delete();
            $this->dispatch('swal:fire', [
                'icon' => 'success',
                'title' => 'Arquivo deletado com sucesso.'
            ]);
        }else{
            $arquivo = $this->inscricao->arquivo($documento);
            if (Storage::exists($arquivo->caminho)) {
                Storage::delete($arquivo->caminho);
            }
            if(!is_null($arquivo->avaliacao)){
                $arquivo->avaliacao->delete();
            }
            $arquivo->delete();
            $this->dispatch('swal:fire', [
                'icon' => 'success',
                'title' => 'Arquivo deletado com sucesso.'
            ]);
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
