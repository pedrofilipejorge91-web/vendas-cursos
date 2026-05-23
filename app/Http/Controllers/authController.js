const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
const { users } = require('../models/userModel');

const SECRET = "meuSegredo";

exports.register = async (req, res) => {
  const { nome, email, senha } = req.body;

  const existe = users.find(u => u.email === email);
  if (existe) {
    return res.status(400).json({ erro: "Email já existe" });
  }

  const hash = await bcrypt.hash(senha, 10);

  const user = {
    id: Date.now(),
    nome,
    email,
    senha: hash
  };

  users.push(user);

  res.json({ mensagem: "Usuário criado" });
};

exports.login = async (req, res) => {
  const { email, senha } = req.body;

  const user = users.find(u => u.email === email);
  if (!user) {
    return res.status(404).json({ erro: "Usuário não encontrado" });
  }

  const valida = await bcrypt.compare(senha, user.senha);
  if (!valida) {
    return res.status(401).json({ erro: "Senha inválida" });
  }

  const token = jwt.sign({ id: user.id }, SECRET, {
    expiresIn: '1d'
  });

  res.json({ token });
};