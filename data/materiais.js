export const materiais = {
  pet: {
    nome: "Plástico",
    subtitulo: "Reciclável",
    categoria: "Vermelha · Plástico",
    descarte: "Descarte correto",

    descricao:
      "Deve ser esvaziada e limpa antes de ser descartada na coleta seletiva de plásticos. Sua reciclagem ajuda a reduzir o consumo de matéria-prima.",

    videoId: "H5rbcjYYTXA",
    tempo: "até 450 anos",

    voceSabia:
      "Cinco garrafas PET viram enchimento para uma camiseta.",

    icone: require("../assets/pet.png"),

    cor: "#EF1717",
  },

  papelao: {
    nome: "Papelão",
    subtitulo: "Reciclável",
    categoria: "Azul · Papel",

    descarte: "Descarte correto",

    descricao:
      "Desmonte a caixa e mantenha seca. Papelão engordurado vai para o resíduo comum.",

    tempo: "de 3 a 6 meses",
      videoId: "aD1gQGmGePw",
    voceSabia:
      "Você sabia? Cada tonelada de papel reciclado evita o corte de cerca de 17 árvores.",

    icone: require("../assets/papelao.png"),

    cor: "#3587F1",

  },

  vidro: {
    nome: "Vidro",
    subtitulo: "Reciclável",
    categoria: "Verde · Vidro",

    descarte: "Descarte correto",

    descricao:
      "Lave e embale cacos em papel grosso identificado para proteger quem faz a coleta.",
     videoId: "5ADi0ZBEkqc",
    tempo: "mais de 1 milhão de anos",

    voceSabia:
      "O vidro é 100% reciclável infinitas vezes, sem perder qualidade.",

    icone: require("../assets/vidro.png"),

    cor: "#4CAF50",
  },

  lata: {
    nome: "Lata de alumínio",
    subtitulo: "Reciclável",
    categoria: "Amarela · Metal",

    descarte: "Descarte correto",

    descricao:
      "Enxágue e amasse para ocupar menos espaço no ecoponto.",

      videoId: "wgPn3kZZtIY",

    tempo: "de 200 a 500 anos",

    voceSabia:
      "Uma lata de alumínio pode voltar às prateleiras em poucas semanas após ser reciclada.",

    icone: require("../assets/lata.png"),

    cor: "#E6B800",
  },

  pilhas: {
    nome: "Pilhas e baterias",
    subtitulo: "Descarte especial",
    categoria: "Vermelha · Especial",

    descarte: "Não descarte no lixo comum",

    descricao:
      "Pilhas e baterias devem ser levadas a pontos de coleta específicos e nunca jogadas no lixo comum. Elas contêm metais pesados que podem contaminar o solo e a água quando descartadas incorretamente.",

      videoId: "db74cbfppPM",
    tempo: "de 100 a 500 anos",

    voceSabia:
      "Você sabia? Uma única pilha pode contaminar até 20 mil litros de água ou 1m² de solo se descartada incorretamente.",

    icone: require("../assets/bateria.png"),

    cor: "#FF8F00",
  },

  oleo: {
    nome: "Óleo de cozinha",
    subtitulo: "Reciclável",
    categoria: "Marrom · Óleo",

    descarte: "Descarte correto",

    descricao:
      "Nunca despeje óleo usado na pia. Armazene em uma garrafa fechada e leve a um ponto de coleta.",

    tempo: "1 Ano ou mais",

    voceSabia:
      "O óleo usado pode ser transformado em produtos como sabão e biodiesel.",

      videoId: "1HccDJzA4fU",

    icone: require("../assets/oleo.png"),

    cor: "#9C6B30", 
  },

  organico: {
    nome: "Resto de alimento",
    subtitulo: "Orgânico",
    categoria: "Marrom · Orgânico",

    descarte: "Descarte correto",

    descricao:
      "Restos de alimentos podem ser destinados à compostagem quando houver essa possibilidade.",

      videoId: "Zue2bN1-Pp8",
    tempo: "dias ou meses",

    voceSabia:
      "Resíduos orgânicos podem virar composto para o solo.",

    icone: require("../assets/resto.png"),

    cor: "#795548",
  },

  espelho: {
    nome: "Espelho",
    subtitulo: "Descarte especial",
    categoria: "Descarte especial",

    descarte: "Não coloque junto com o vidro comum",

    descricao:
      "Espelhos possuem uma composição diferente do vidro comum e normalmente não devem ser colocados na coleta convencional de vidro.",

    videoId: "cKkyuu7ls68",
  
    tempo: "pode levar milhares de anos",

    voceSabia:
      "O espelho possui uma camada metálica que dificulta sua reciclagem junto ao vidro comum.",

    icone: require("../assets/espelho.png"),

    cor: "#607D8B",
  },
};