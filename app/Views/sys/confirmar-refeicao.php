<div class="mb-3">
  <h2 class="card-title mb-0">Controle de Refeições</h2>
</div>

<div class="container py-5">

  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">

      <div class="card shadow-sm border-0">
        <div class="card-body text-center py-5">

          <button id="btn-iniciar-leitura" class="btn btn-primary btn-lg px-5">
            <i class="mdi mdi-qrcode-scan me-2"></i> Ler QR Code
          </button>

          <div id="reader-container" class="d-none mt-4">
            <div id="reader" style="width: 100%; max-width: 400px; margin: 0 auto;"></div>
            <button id="btn-cancelar-leitura" class="btn btn-outline-secondary mt-3 w-100">
              <i class="mdi mdi-close-circle-outline me-1"></i> Cancelar Leitura
            </button>
          </div>

          <div id="result-container" class="d-none mt-4">

            <div class="mb-3">
              <img id="foto-aluno" alt="Foto do aluno" class="rounded-circle shadow"
                style="width: 180px; height: 180px; object-fit: cover;">
            </div>

            <div class="mt-3">
              <p id="nome-aluno" class="fw-bold fs-4 mb-1"></p>
              <p id="data-refeicao" class="text-muted mb-1"></p>
              <p id="status-refeicao" class="fw-semibold fs-5"></p>
            </div>

            <div id="container-acoes" class="justify-content-center mt-4">
              <button id="btn-confirmar" class="btn btn-success btn-lg px-5">
                <i class="mdi mdi-check-circle-outline me-1"></i> Confirmar
              </button>
            </div>

            <button id="btn-limpar" class="btn btn-outline-primary mt-4 px-4">
              <i class="mdi mdi-refresh me-1"></i> Nova Leitura
            </button>
          </div>

          <div id="status-msg" class="mt-4 fw-semibold fs-6"></div>

        </div>
      </div>

    </div>
  </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const btnIniciar = document.getElementById('btn-iniciar-leitura');
    const btnCancelar = document.getElementById('btn-cancelar-leitura');
    const readerContainer = document.getElementById('reader-container');
    const resultContainer = document.getElementById('result-container');
    const btnConfirmar = document.getElementById('btn-confirmar');
    const btnLimpar = document.getElementById('btn-limpar');
    const containerAcoes = document.getElementById('container-acoes');
    const statusMsg = document.getElementById('status-msg');

    const fotoAluno = document.getElementById('foto-aluno');
    const nomeAluno = document.getElementById('nome-aluno');
    const dataRefeicao = document.getElementById('data-refeicao');
    const statusRefeicao = document.getElementById('status-refeicao');

    let html5QrcodeScanner = null;
    let refeicaoId = null;

    const csrfHeader = "<?= csrf_header() ?>";
    const csrfHash = "<?= csrf_hash() ?>";

    function iniciarLeitura() {
      resetTela();
      btnIniciar.classList.add('d-none');
      readerContainer.classList.remove('d-none');

      html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", {
          fps: 10,
          qrbox: {
            width: 250,
            height: 250
          }
        },
        false
      );

      html5QrcodeScanner.render(onScanSuccess, () => {});
    }

    function pararLeitura() {
      if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
        html5QrcodeScanner = null;
      }
      readerContainer.classList.add('d-none');
      btnIniciar.classList.remove('d-none');
    }

    function resetTela() {
      resultContainer.classList.remove('d-flex');
      resultContainer.classList.add('d-none');
      containerAcoes.classList.remove('d-none')
      btnIniciar.classList.remove('d-none');
      statusMsg.innerHTML = '';
      statusRefeicao.textContent = '';
    }

    async function onScanSuccess(decodedText) {
      pararLeitura();
      statusMsg.innerHTML = 'Consultando dados da refeição...';
      statusMsg.className = 'text-warning';

      try {
        const response = await fetch("<?= base_url('/sys/controle-refeicoes/validar') ?>", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            [csrfHeader]: csrfHash
          },
          body: JSON.stringify({
            codigo: decodedText
          })
        });

        const res = await response.json();

        if (res.success) {
          const data = res.data;
          refeicaoId = data.id || null;

          fotoAluno.src = data.aluno.foto_url || '';
          nomeAluno.textContent = "Aluno: " + data.aluno.nome;
          dataRefeicao.textContent = "Data: " + data.data_refeicao;
          statusRefeicao.textContent = data.mensagem_status;

          if (data.pode_servir) {
            statusRefeicao.className = "fw-semibold";
          } else {
            containerAcoes.classList.add("d-none");
            statusRefeicao.className = "text-danger fw-bold";
          }

          resultContainer.classList.remove('d-none');
          statusMsg.innerHTML = '';
        } else {
          statusMsg.innerHTML = res.error || 'Refeição não encontrada.';
          statusMsg.className = 'text-danger';
          btnIniciar.classList.remove('d-none');
        }
      } catch (err) {
        statusMsg.innerHTML = 'Erro na requisição: ' + err.message;
        statusMsg.className = 'text-danger';
        btnIniciar.classList.remove('d-none');
      }
    }

    async function handleClick() {
      if (!refeicaoId) return;
      statusMsg.innerHTML = 'Processando...';
      statusMsg.className = 'text-warning';

      try {
        const response = await fetch("<?= base_url('/sys/controle-refeicoes/confirmar') ?>", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            [csrfHeader]: csrfHash
          },
          body: JSON.stringify({
            id: refeicaoId
          })
        });

        const data = await response.json();

        if (!data.success) {
          statusMsg.innerHTML = data.error || 'Erro ao processar ação.';
          statusMsg.className = 'text-danger';
          return;
        }

        statusMsg.innerHTML = 'Refeição confirmada com sucesso!';
        statusMsg.className = 'text-success';
        containerAcoes.classList.add('d-none');
      } catch (err) {
        statusMsg.innerHTML = 'Erro na requisição: ' + err.message;
        statusMsg.className = 'text-danger';
      }
    }

    btnIniciar.addEventListener('click', iniciarLeitura);
    btnCancelar.addEventListener('click', pararLeitura);
    btnConfirmar.addEventListener('click', handleClick);
    btnLimpar.addEventListener('click', resetTela);
  });
</script>