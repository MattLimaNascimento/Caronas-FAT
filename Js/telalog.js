const buttons = document.querySelectorAll(".menu__item");
const wrapper = document.querySelector(".wrapper");
const btnPopup = document.getElementById("Caroneiro");
const iconClose = document.querySelector(".icon-close");
const iconClose2 = document.getElementById("icon-close-2");
const iconClose3 = document.getElementById("icon-close3");
const login_confirm = document.getElementById("btn_login");
const input = document.getElementById("n_registro_login");
const input1 = document.getElementById("n_registro");
const menu1 = document.querySelector(".menu1");
const menu2 = document.querySelector(".menu2");
const tela1 = document.getElementById("tela1");
const tela2 = document.getElementById("tela2");
const tela1_cel = document.getElementById("tela1_cel");
const tela2_cel = document.getElementById("tela2_cel");
const anuncio_caronas = document.getElementById("anuncio-caronas");
const login_link = document.querySelector(".login-link");
const registro_link = document.querySelector(".register-link");
const seguranca = document.querySelector(".seguranca");
const btn_registro = document.querySelector(".container-car-register");
const wrapper2 = document.querySelector(".wrapper_menu2");
const selected = document.getElementById("selected");
const optionsContainer = document.getElementById("options-container");
const optionsList = document.querySelectorAll(".option");
const selected1 = document.getElementById("selected2");
const optionsContainer1 = document.getElementById("options-container2");
const optionsList1 = document.querySelectorAll(".option1");
const register_car = document.querySelector("Button_register");
const action = document.querySelector(".action_user");
const sinal = document.getElementById("sinal");
const reservas = document.querySelector('.reservas');
const container_confirmados = document.querySelector(".container-confirmados");
const indicator = document.querySelector('.nav-indicator');
const items = document.querySelectorAll('.nav-item');
const cardWrapper2 = document.getElementById('anuncios_caronas_temp');
let activeButton = document.querySelector(".menu__item.active");

// // Mandar Notificações
// Notification.requestPermission().then(perm => {
//   if (perm === "granted") {
//     new Notification("Notificação Teste", {
//       body: "Sua carona exprirará em 10 minutos!",
//       icon: '/CSS/Imagens/Caronas_FAT icon.png'
//     });
//   } else {
//     alert("Por favor libere as permissões de notificações para melhor experiência com o app!");
//   }
// });

// // Geolocalização
// const getlocation = () => {
//   if (navigator.geolocation) {
//     navigator.geolocation.getCurrentPosition(
//           (position) => {
//             console.log(position);
//           });
//   }
// }
// getlocation();

$.ajax({
  url: "/PHP/infos.php",
  type: "post",
  success: (resultado) => {
    var res = JSON.parse(resultado);
    action.innerHTML += res[0];
    var event = new Event("infosCarregadas");
    document.dispatchEvent(event);
  },
});

//Pegar o dia da semana atual
var dataAtual = new Date();
var numeroDia = dataAtual.getDay();

var diasSemana = [
  "Domingo",
  "Segunda",
  "Terca",
  "Quarta",
  "Quinta",
  "Sexta",
  "Sábado",
];
var diaSemanaAtual = diasSemana[numeroDia];
// Obtém o horário atual
var horaAtual = dataAtual.getHours();
var minutosAtual = dataAtual.getMinutes();

// Formata a hora e os minutos no formato "horas:minutos"
var horarioAtual = horaAtual + ":" + minutosAtual;

// Função para verificar o horário dos cards
verificarHorarios = () => {
  // Obter todos os elementos com o ID "cards_temp"
  const cards = document.querySelectorAll("#cards_temp");

  // Obter o horário atual
  const horarioAtual = new Date();
  const horarioAtualFormatado = horarioAtual.getHours() * 60 + horarioAtual.getMinutes();

  // Obtém o horário atual
  var horaAtual = dataAtual.getHours();
  var minutosAtual = dataAtual.getMinutes();

  // Formata a hora e os minutos no formato "horas:minutos"
  var horario_Atual = horaAtual + ":" + minutosAtual;

  // Verificar se é meia-noite (horário atual igual a 00:00)
  if (horarioAtual.getHours() === 0 && horarioAtual.getMinutes() === 0) {
    cards.forEach((card) => {
      const nome_motor = card.querySelector(".name");
      const nomeMotorista = nome_motor.textContent.trim();

      // Remover o card do DOM
      card.remove();

      // Enviar a requisição AJAX
      $.ajax({
        url: "/PHP/anuncios.php",
        type: "post",
        data: {
          nome_motor: nomeMotorista,
          horario_atual: horarioAtualFormatado,
          dia_SemanaAtual: getDiaSemanaAtual(),
          tipo: 3
        }
      });
    });

    return; // Encerrar a função após remover os cards e enviar as requisições AJAX
  }

  let cardsRemovidos = false; // Variável de controle para verificar se os cards foram removidos

  // Percorrer os cards
  for (let i = 0; i < cards.length; i++) {
    const card = cards[i];

    const horarioElemento = card.querySelector("#horario_temp");
    const horarioCard = horarioElemento.textContent.trim();
    const horarioCardSplit = horarioCard.split(":");
    const horarioCardHours = parseInt(horarioCardSplit[0]);
    const horarioCardMinutes = parseInt(horarioCardSplit[1]);

    const horarioCardDate = new Date();
    horarioCardDate.setHours(horarioCardHours);
    horarioCardDate.setMinutes(horarioCardMinutes);

    
    // Verificar se o horário do card já passou em relação ao horário atual
    if (horarioAtual > horarioCardDate) {
      const nome_motor = card.querySelector(".name");
      const nomeMotorista = nome_motor.textContent.trim();

      // Enviar a requisição AJAX
      $.ajax({
        url: "/PHP/anuncios.php",
        type: "post",
        data: {
          nome_motor: nomeMotorista,
          horario_atual: horario_Atual,
          dia_SemanaAtual: getDiaSemanaAtual(),
          tipo: 3
        }, 
        success: (resultado) => {
          console.log(resultado);
        }
      });
      
      // Remover o card do DOM
      card.remove();


      cardsRemovidos = true; // Atualizar a variável de controle
    }
  }

  // Verificar se os cards foram removidos antes de recarregar a página
  if (cardsRemovidos) {
    location.reload(); // Recarregar a página
  }
}

// Função para obter o dia da semana atual
function getDiaSemanaAtual() {
  const diasSemana = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"];
  const diaSemanaAtual = new Date().getDay();
  return diasSemana[diaSemanaAtual];
}

// Adicionar um ouvinte de eventos para o movimento do mouse
document.addEventListener("mousemove", verificarHorarios);


$.ajax({
  url: "/PHP/anuncios.php",
  type: "post",
  data: {
    horario_atual: horarioAtual,
    dia_SemanaAtual: diaSemanaAtual,
    tipo: 2
  },
  async: false,
  success: (resultado) => {
    var cards = JSON.parse(resultado);

    // Limpa o conteúdo existente do cardWrapper
    cardWrapper2.innerHTML = "";

    for (var i = 0; i < cards.length; i++) {
      cardWrapper2.innerHTML += cards[i];
    }
  },
});

document.addEventListener("infosCarregadas", () => {
  $.ajax({
    url: "/PHP/reg_caronas.php",
    type: "POST",
    data: {
      tipo: "conferir",
    },
    success: (resultado) => {
      if (resultado == "Você tem uma reserva.") {
        $(".sinal").addClass("active");
      } else if (resultado == "Você não tem uma reserva.") {
      }
    },
  });

  $.ajax({
    url: "/PHP/reg_caronas.php",
    type: "POST",
    data: {
      tipo: "Confirmados",
      tipo2: 2
    },
    success: (resultado) => {
      if (resultado > 0) {
        $(".sinal2").text(resultado).addClass("active");
      }
    },
  });

  $('#Carona_ok').click(() => {
    var dataAtual = new Date();
    var numeroDia = dataAtual.getDay();

    var diasSemana = [
      "Domingo",
      "Segunda",
      "Terca",
      "Quarta",
      "Quinta",
      "Sexta",
      "Sábado",
    ];
    var diaSemanaAtual = diasSemana[numeroDia];
    $.ajax({
      url: '/PHP/reg_caronas.php',
      type: 'POST',
      data: {
        data_hoje: diaSemanaAtual,
        tipo: 'conferir2',
      },
      success: (resultado) => {
        if (resultado != 'Nenhum resultado encontrado.') {
          const card = JSON.parse(resultado);
          // Limpa o conteúdo existente do cardWrapper
          reservas.innerHTML = card[0];
          reservas.classList.add('active');
          var event = new Event("infosReserva");
          document.dispatchEvent(event);
        } else {
          alert("Você não tem nenhuma carona reservada.");
        }
      }
    });
  });

  $("#Caronas-confirmadas").click(() => {
    $.ajax({
      url: "/PHP/reg_caronas.php",
      type: "POST",
      data: {
        tipo: "Confirmados",
        tipo2: 1
      },
      success: (resultado) => {
        if (resultado != 'Não há nenhuma carona') {
          var cards = JSON.parse(resultado);
          // Limpa o conteúdo existente do cardWrapper
          container_confirmados.innerHTML = "";

          for (var i = 0; i < cards.length; i++) {
            container_confirmados.innerHTML += cards[i];
          }

          container_confirmados.classList.add('active');

          $('#icon-close-4').click(() => {
            container_confirmados.classList.remove('active');
          });

          // Dispara o evento personalizado 'cardsCarregados' após adicionar os cards
          var event = new Event("cardsConfirmados");
          document.dispatchEvent(event);
        } else {
          alert("Você não tem nenhuma carona confirmada.");
        }
      },
    });
  });
});

var isPageLoading = true;
var tempoInatividade = 30 * 60 * 1000; // 30 minutos
var temporizadorInatividade;

$.ajax({
  url: "/PHP/sessao.php",
  type: "post",
  data: {
    tipo: "login",
  },
  success: (resultado) => {
    if (resultado == "sessao temporária") {
      // Define um cookie com o valor indicando que a sessão deve ser destruída
      document.cookie = "destruir_sessao=true";
      iniciarTemporizador(); // Inicia o temporizador

      $(document).on("mousemove", redefinirTemporizador);

      $(window).on("beforeunload", function () {
        if (!isPageLoading) {
          var destruirSessao = getCookie("destruir_sessao");
          if (destruirSessao === "true") {
            destruirSessao();
          }
        }
      });

      function iniciarTemporizador() {
        temporizadorInatividade = setTimeout(destruirSessao, tempoInatividade);
      }

      function redefinirTemporizador() {
        clearTimeout(temporizadorInatividade);
        iniciarTemporizador();
      }

      function getCookie(name) {
        var cookieArr = document.cookie.split("; ");
        for (var i = 0; i < cookieArr.length; i++) {
          var cookiePair = cookieArr[i].split("=");
          if (cookiePair[0] === name) {
            return decodeURIComponent(cookiePair[1]);
          }
        }
        return "";
      }

      function destruirSessao() {
        // Faz a requisição AJAX para destruir a sessão
        $.ajax({
          url: "/PHP/sair.php",
          type: "post",
          success: function (response) {
            console.log("Sessão destruída com sucesso");
          },
          error: function (xhr) {
            console.log("Erro ao destruir a sessão: " + xhr.status);
          }
        });
      }
    } else if (resultado == 'Não há sessão') {
      window.location = '/index.html';
    }
    isPageLoading = false; // A página terminou de carregar
  },
});

$.ajax({
  url: "/PHP/sessao.php",
  type: "post",
  data: {
    tipo: "motorista",
  },
  success: (resultado) => {
    if (resultado == "Há sessão") {
      btn_registro.classList.add("desactive");
    } else {
      btn_registro.classList.remove("desactive");
    }
  },
});

buttons.forEach((item) => {
  const text = item.querySelector(".menu__text");

  setLineWidth(text, item);

  window.addEventListener("resize", () => {
    setLineWidth(text, item);
  });
  item.addEventListener("click", function () {
    if (this.classList.contains("active")) return;

    this.classList.add("active");

    if (activeButton) {
      activeButton.classList.remove("active");
      activeButton.querySelector(".menu__text").classList.remove("active");
    }

    handleTransition(this, text);
    activeButton = this;
  });
});

function setLineWidth(text, item) {
  const lineWidth = text.offsetWidth + "px";
  item.style.setProperty("--lineWidth", lineWidth);
}
function handleTransition(item, text) {
  item.addEventListener("transitionend", (e) => {
    if (e.propertyName != "flex-grow" || !item.classList.contains("active"))
      return;

    text.classList.add("active");
  });
}
// abrir e fechar popup

btnPopup.addEventListener("click", () => {
  if (wrapper.classList.contains("active-popup")) {
    wrapper.classList.remove("active-popup");
  } else {
    wrapper.classList.add("active-popup");
  }
});
iconClose.addEventListener("click", () => {
  wrapper.classList.remove("active-popup");
});
iconClose2.addEventListener("click", () => {
  tela1.click();
  tela1_cel.click();
  if (wrapper.classList.contains("active-popup")) {
    wrapper.classList.remove("active-popup");
  }
});

// Movimentação de troca de Telas
tela2_cel.addEventListener("click", () => {
  menu1.classList.add("desactivate");
  $.ajax({
    url: "/PHP/sessao.php",
    type: "post",
    data: {
      tipo: "motorista",
    },
    success: (resultado) => {
      if (resultado == "Há sessão") {
        menu2.classList.add("active");
      } else {
        seguranca.classList.add("active");
      }
    },
  });
});

tela1_cel.addEventListener("click", () => {
  menu1.classList.remove("desactivate");
  $.ajax({
    url: "/PHP/sessao.php",
    type: "post",
    data: {
      tipo: "motorista",
    },
    success: (resultado) => {
      if (resultado == "Há sessão") {
        menu2.classList.remove("active");
      } else {
        seguranca.classList.remove("active");
      }
    },
  });
});

tela2.addEventListener("click", () => {
  menu1.classList.add("desactivate");
  $.ajax({
    url: "/PHP/sessao.php",
    type: "post",
    data: {
      tipo: "motorista",
    },
    success: (resultado) => {
      if (resultado == "Há sessão") {
        menu2.classList.add("active");
      } else {
        seguranca.classList.add("active");
      }
    },
  });
});

tela1.addEventListener("click", () => {
  menu1.classList.remove("desactivate");
  $.ajax({
    url: "/PHP/sessao.php",
    type: "post",
    data: {
      tipo: "motorista",
    },
    success: (resultado) => {
      if (resultado == "Há sessão") {
        menu2.classList.remove("active");
      } else {
        seguranca.classList.remove("active");
      }
    },
  });
});

// troca para login e registro
login_link.addEventListener("click", () => {
  menu1.classList.add("desactivate");
  $.ajax({
    url: "/PHP/sessao.php",
    type: "post",
    data: {
      tipo: "motorista",
    },
    success: (resultado) => {
      if (resultado == !"Há sessão") {
        seguranca.classList.add("active");
      }
    },
  });
  wrapper.classList.remove("active-popup");
  tela2.click();
  tela2_cel.click();
});

registro_link.addEventListener("click", () => {
  menu1.classList.remove("desactivate");
  seguranca.classList.remove("active");
  wrapper.classList.add("active-popup");
  tela1.click();
});

// Confirmação de Login
login_confirm.addEventListener("click", (e) => {
  e.preventDefault();
  $.ajax({
    url: "/PHP/login.php",
    type: "post",
    data: {
      n_registro: $("#n_registro_login").val(),
      placa_carro: $("#placa_carro_login").val(),
    },
    success: (resultado) => {
      if (resultado == "Existe") {
        seguranca.classList.remove("active");
        menu2.classList.add("active");
        btn_registro.classList.add("desactive");
      } else if (resultado == "Não existe" || resultado == "Motorista inválido") {
        alert(
          "Número de Registro ou Placa não foram encontrados ou não estão ligados ao usuário, por favor verifique novamente! "
        );
        window.location.reload();
      }
    },
  });
});

anuncio_caronas.addEventListener("click", () => {
  if (wrapper2.classList.contains("active-popup")) {
    wrapper2.classList.remove("active-popup");
  } else {
    wrapper2.classList.add("active-popup");
  }
});

iconClose3.addEventListener("click", () => {
  wrapper2.classList.remove("active-popup");
});

selected.addEventListener("click", () => {
  optionsContainer.classList.toggle("active");
});

optionsList.forEach((o) => {
  o.addEventListener("click", () => {
    optionsList.forEach((radio) => {
      radio.querySelector("input").checked = false;
    });
    const qtdAcentos = document.getElementById("qtd_acentos");

    o.querySelector("input").checked = true;
    selected.innerHTML = o.querySelector("label").innerHTML;

    optionsContainer.classList.remove("active");

    if (o.querySelector("input").value === "moto") {
      qtdAcentos.setAttribute("max", "1");
      qtdAcentos.setAttribute("value", "1");
    } else if (o.querySelector("input").value === "carro") {
      qtdAcentos.setAttribute("max", "6");
      qtdAcentos.setAttribute("value", "");
    }
  });
});

// Define o tempo de desativação do evento de clique em milissegundos
const disableTime = 500; // 1 segundo

// Função de manipulador de clique
function clickHandler() {
  // Variáveis do Container Anuncios das Caronas
  const cardWrapper = document.querySelector(".card-wrapper");

  // Obtém o valor do dia selecionado
  const selectedDay = this.querySelector("input").value;


  //Pegar o dia da semana atual
  var dataAtual = new Date();
  var numeroDia = dataAtual.getDay();

  var diasSemana = [
    "Domingo",
    "Segunda",
    "Terca",
    "Quarta",
    "Quinta",
    "Sexta",
    "Sábado",
  ];
  var diaSemanaAtual = diasSemana[numeroDia];

  // Obtém o horário atual
  var dataAtual = new Date();
  var horaAtual = dataAtual.getHours();
  var minutosAtual = dataAtual.getMinutes();

  // Formata a hora e os minutos no formato "horas:minutos"
  var horarioFormatado = horaAtual + ":" + String(minutosAtual).padStart(2, '0');

  $.ajax({
    url: "/PHP/anuncios.php",
    type: "post",
    data: {
      dia_semana: selectedDay,
      dia_SemanaAtual: diaSemanaAtual,
      horario_atual: horarioFormatado,
      tipo: 1
    },
    async: false,
    success: function (resultado) {
      
      var cards = JSON.parse(resultado);

      // Limpa o conteúdo existente do cardWrapper
      cardWrapper.innerHTML = "";

      for (var i = 0; i < cards.length; i++) {
        cardWrapper.innerHTML += cards[i];
      }

      // Dispara o evento personalizado 'cardsCarregados' após adicionar os cards
      var event = new Event("cardsCarregados");
      document.dispatchEvent(event);
    },
  });

  // Desativa o evento de clique temporariamente
  disableClickEvent();
}

// Função para desativar temporariamente o evento de clique
function disableClickEvent() {
  optionsList1.forEach((option) => {
    option.removeEventListener("click", clickHandler);
  });

  setTimeout(() => {
    optionsList1.forEach((option) => {
      option.addEventListener("click", clickHandler);
    });
  }, disableTime);
}

selected1.addEventListener("click", () => {
  optionsContainer1.classList.toggle("active");
});

optionsList1.forEach((o) => {
  const currentDate = new Date();
  const currentDayOfWeekIndex = currentDate.getDay();
  let selectedDayOfWeekIndex;

  if (currentDayOfWeekIndex === 0 || currentDayOfWeekIndex === 6) {
    selectedDayOfWeekIndex = 0;  // Domingo ou sábado
  } else {
    selectedDayOfWeekIndex = currentDayOfWeekIndex - 1;  // Segunda a sexta-feira
  }

  // Obtenha a primeira opção do optionsList1
  const primeiraOpcao = optionsList1[selectedDayOfWeekIndex];
  
  primeiraOpcao.click();

  // if (o.classList.contains("sexta")) {
  //   o.click();
  //   console.log(o);
  // }

  o.addEventListener('click', clickHandler);

  o.addEventListener("click", () => {
    optionsList1.forEach(
      (radio) => (radio.querySelector("input").checked = false)
    );

    o.querySelector("input").checked = true;

    const selectedDayOfWeek = o.querySelector("input").value;

    const currentDate = new Date();
    const currentDayOfWeekIndex = currentDate.getDay();
    const selectedDayOfWeekIndex = getSelectedDayOfWeekIndex(selectedDayOfWeek);

    let dayDiff;
    if (currentDayOfWeekIndex <= selectedDayOfWeekIndex) {
      dayDiff = selectedDayOfWeekIndex - currentDayOfWeekIndex;
    } else {
      dayDiff = 7 - (currentDayOfWeekIndex - selectedDayOfWeekIndex);
    }

    const selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() + dayDiff);

    const selectedDay = selectedDate.getDate().toString().padStart(2, "0");
    const selectedMonth = (selectedDate.getMonth() + 1).toString().padStart(2, "0");

    selected1.innerHTML = `${selectedDayOfWeek} ${selectedDay}/${selectedMonth}`;
    optionsContainer1.classList.remove("active");
  });

});

function getSelectedDayOfWeekIndex(selectedDayOfWeek) {
  const daysOfWeek = ["Domingo", "Segunda", "Terca", "Quarta", "Quinta", "Sexta", "Sabado"];
  return daysOfWeek.indexOf(selectedDayOfWeek);
};

mostrarValores = (event) => {
  event.preventDefault();

  const destino = document.getElementById("destiny").value;
  const origem = document.getElementById("origin").value;
  const preco = document.getElementById("preco").value;
  const qtd_acentos = document.getElementById("qtd_acentos").value;
  const carro = document.getElementById("carro");
  const moto = document.getElementById("moto");
  let categoria;

  if (carro.checked) {
    categoria = carro.value;
  } else if (moto.checked) {
    categoria = moto.value;
  }

  const time1 = document
    .getElementById("time1")
    .querySelector("input[type='time']").value;
  const time2 = document
    .getElementById("time2")
    .querySelector("input[type='time']").value;
  const time3 = document
    .getElementById("time3")
    .querySelector("input[type='time']").value;
  const time4 = document
    .getElementById("time4")
    .querySelector("input[type='time']").value;
  const time5 = document
    .getElementById("time5")
    .querySelector("input[type='time']").value;
  const fixo = document
    .getElementById("fixo")
    .checked;

  if (categoria == undefined) {
    alert("Por favor selecione o tipo de veículo para a carona!");
    return;
  } else if (
    qtd_acentos == 0
  ) {
    alert('Por favor coloque a quantidade de acentos que deseja');
    return;
  }
  else if (
    preco == 0
  ) {
    if (confirm('Você tem certeza que a carona será de graça?')) {
    } else {
      return;
    }
  }
  
  if (
    time1 == "00:00" &&
    time2 == "00:00" &&
    time3 == "00:00" &&
    time4 == "00:00" &&
    time5 == "00:00"
  ) {
    alert("Por favor, preencha pelo menos um horário.");
  } else {
    $.ajax({
      url: "/PHP/informacoes.php",
      type: "post",
      data: {
        destino: destino,
        Preco: preco,
        Tipo_de_Veiculo: categoria,
        Vagas_Totais: qtd_acentos,
        Segunda: time1,
        Terca: time2,
        Quarta: time3,
        Quinta: time4,
        Sexta: time5,
        origem: origem,
        Fixo: fixo,
        tipo: 1
      },
      success: (resultado) => {
        if (resultado == "Já existe um registro!") {
          alert("Só pode até 1 cadastro de carona!");
          window.location.reload();
        } else if (resultado == "Carona cadastrada com sucesso!") {
          alert("Carona cadastrada com sucesso!");
          window.location.reload();
        } else {
          alert(`Erro Information: ${resultado}`);
        }
      },
    });
  }
};

formatarPlaca = (id) => {
  var placa = document.getElementById(id).value;
  placa = placa.replace(/[^a-zA-Z0-9\-]/g, "");
  if (placa.length > 3) {
    var letras = placa.substring(0, 3).replace(/[^a-zA-Z]/g, "");
    var numeros = placa.substring(3).replace(/[^0-9a-zA-Z]/g, "");
    numeros = numeros.replace(/([a-zA-Z])([0-9])/g, "$1/$2"); // adiciona a barra entre letra e número
    placa = letras + "-" + numeros;
  }
  document.getElementById(id).value = placa.toUpperCase();
}

limitMaxLength = (inputElement) => {
  const maxLength = 11;
  const currentValue = inputElement.value.replace(/\D/g, "");

  if (currentValue.length > maxLength) {
    inputElement.value = currentValue.slice(0, maxLength);
  }
};

input.addEventListener("input", () => {
  limitMaxLength(input);
});

input1.addEventListener("input", () => {
  limitMaxLength(input1);
});

$(document).on("click", ".button", (e) => {
  var button = $(e.target);
  var buttonId = button.attr("data-button-id");
  var card = button.closest(".card");
  var name = card.find(".name").text();
  var horario = card.find(".description .horario").text();
  var sinal = document.getElementById("sinal");

  if (button.attr("id") == "temp") {
    if (sinal.classList.contains('active')) {
      alert('Não é possível marcar esta carona, pois você já possui uma carona marcada!');
      return;
    }
    if (confirm("Tem certeza que deseja confirmar para esta carona?")) {
      $.ajax({
        url: "/PHP/reg_caronas.php",
        type: "POST",
        data: {
          tipo: "reserva",
          acento: buttonId,
          motorista: name,
          horario: horario,
          Reserva: 'temp'
        },
        success: (res) => {
          if (res == 'Reserva feita com sucesso!') {
            alert(res);
            window.location.reload();
          } else if (res == 'Error') {
            alert('Error');
          }
        },
      });
    }
  } else {
    if (sinal.classList.contains('active')) {
      alert('Não é possível marcar esta carona, pois você já possui uma carona marcada!');
      return;
    }
    if (confirm("Tem certeza que deseja confirmar para esta carona?")) {
      $.ajax({
        url: "/PHP/reg_caronas.php",
        type: "POST",
        data: {
          tipo: "reserva",
          acento: buttonId,
          motorista: name,
          horario: horario
        },
        success: (res) => {
          if (res == 'Reserva feita com sucesso!') {
            alert(res);
            window.location.reload();
          } else if (res == 'Error') {
            alert('Error');
          }
        },
      });
    }
  }
});

document.getElementById("myForm").addEventListener("submit", function (event) {
  event.preventDefault(); // Impede o envio padrão do formulário
  $.ajax({
    url: "/PHP/registro.php",
    type: "POST",
    data: {
      tipo: "motorista",
      n_registro: $("#n_registro").val(),
      placa_carro: $("#placa_carro").val(),
    },
    success: (r) => {
      if (r == "Usuario existente") {
        alert(
          "Já existe um registro com este número de registro ou placa de carro!"
        );
        window.location.href = "/HTML/telalog.html";
      } else if (r == "Sucesso!") {
        alert("Caroneiro cadastrado com sucesso!");
        window.location.href = "/HTML/telalog.html";
      }
    },
  });
});

document.addEventListener("infosCarregadas", () => {
  const logout = document.getElementById("logout-btn");
  logout.addEventListener("click", () => {
    if (confirm("Tem certeza que deseja sair?")) {
      $.ajax({
        url: "/PHP/sair.php",
        type: "post",
        success: (resultado) => {
          window.location.reload();
        },
      });
    }
  });
});

document.addEventListener('infosReserva', () => {
  $('#icon-close-3').click(() => {
    reservas.classList.remove('active');
  });
  $('#desmarcar').click(() => {
    if (confirm('Tem certeza que deseja desmarcar sua carona?')) {
      var horario = $('.horario_reserva').text();
      $.ajax({
        url: '/PHP/reg_caronas.php',
        type: 'POST',
        data: {
          tipo: 'Cancelar',
          horario: horario
        },
        success: (res) => {
          if (res == 'Carona desmarcada com sucesso!') {
            alert('Carona desmarcada com sucesso!');
            window.location.reload();
          } else if (res == 'Você não possui carona marcada.') {
            alert('Você não possui carona marcada!');
          }
        }
      });
    }
  });
});

function handleIndicator(el) {
  items.forEach(item => {
    item.classList.remove('is-active');
    item.removeAttribute('style');
  });

  indicator.style.width = `${el.offsetWidth}px`;
  indicator.style.left = `${el.offsetLeft}px`;
  indicator.style.backgroundColor = el.getAttribute('active-color');

  el.classList.add('is-active');
  el.style.color = el.getAttribute('active-color');
}

items.forEach((item, index) => {
  item.addEventListener('click', (e) => { handleIndicator(e.target) });
  item.classList.contains('is-active') && handleIndicator(item);
});