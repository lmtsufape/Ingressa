<div class="modal fade" id="listar" tabindex="-1" aria-labelledby="listar" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Escolher listagem</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
                  
            <a title="Listar candidatos da chamada" href="{{route('chamadas.candidatos', ['sisu_id' => $sisu->id, 'chamada_id' => $chamada->id])}}"><img class="m-1 " width="30" src="{{asset('img/Grupo 1682.svg')}}" alt="Icone de listar candidatos"> Listar por cursos</a>

            <a href="">Listar candidatos</a>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
</div>
        
