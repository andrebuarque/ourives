<?php
namespace fachada;

use Doctrine\Common\Collections\ArrayCollection;
use modelo\entidades\Usuario;
use modelo\entidades\Permissao;

use modelo\negocio\PermissaoService;

use modelo\entidades\Perfil;

use modelo\negocio\MenuService;

use modelo\negocio\UsuarioService;
use modelo\negocio\PerfilService;
use modelo\entidades\Menu;
use modelo\negocio\ClienteService;
use modelo\entidades\Cliente;
use modelo\negocio\ProdutoService;
use modelo\entidades\Produto;
use modelo\negocio\CategoriaOSService;
use modelo\entidades\CategoriaOS;
use modelo\negocio\OrdemServicoService;
use modelo\entidades\OrdemServico;
use modelo\negocio\ImagemOSService;
use modelo\entidades\ImagemOS;

/**
 * @author Buarque
 */
class Fachada {
	
	/**
	 * @var UsuarioService
	 */
	private $usuarioService;
	
	/**
	 * @var PerfilService
	 */
	private $perfilService;
	
	/**
	 * @var MenuService
	 */
	private $menuService;
	
	/**
	 * @var PermissaoService
	 */
	private $permissaoService;
	
	/**
	 * @var ClienteService
	 */
	private $clienteService;
	
	/**
	 * @var ProdutoService
	 */
	private $produtoService;
	
	/**
	 * @var CategoriaOSService
	 */
	private $categoriaOSService;
	
	/**
	 * @var OrdemServicoService
	 */
	private $ordemServicoService;
	
	/**
	 * @var ImagemOSService
	 */
	private $imagemOSService;
	
	public function __construct() {
		$this->usuarioService = new UsuarioService();
		$this->perfilService = new PerfilService();
		$this->menuService = new MenuService();
		$this->permissaoService = new PermissaoService();
		$this->clienteService = new ClienteService();
		$this->produtoService = new ProdutoService();
		$this->categoriaOSService = new CategoriaOSService();
		$this->ordemServicoService = new OrdemServicoService();
		$this->imagemOSService = new ImagemOSService();
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listar() {
		return $this->perfilService->listar();
	}
	
	/**
	 * @return ArrayCollection
	 */
	public function listarPerfisAtivos(){
		return $this->perfilService->listarPerfisAtivo();
	}
	
	public function inserirPerfil(Perfil $perfil){
		return $this->perfilService->inserirPerfil($perfil);
	}
	
	/**
	 * @param Perfil $perfil
	 * @return Perfil
	 */
	public function atualizarPerfil(Perfil $perfil){
		$this->permissaoService->excluirPermissoesPerfil($perfil);
		return $this->perfilService->atualizarPerfil($perfil);
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listarMenu(){
		return $this->menuService->listarMenu();
	}
	
	public function buscarMenu(Menu $menu) {
		return $this->menuService->buscarMenu($menu);
	}
	
	/**
	 * @param Perfil $perfil
	 * @return \modelo\entidades\Perfil
	 */
	public function buscarPerfil(Perfil $perfil){
		return $this->perfilService->buscarPerfil($perfil);
	}
	
	/**
	 * @param Permissao $permissao
	 * @return Permissao
	 */
	public function inserirPermissao(Permissao $permissao){
		return $this->permissaoService->inserirPermissao($permissao);
	}
	
	/**
	 * @param Usuario $usuario
	 * @return Usuario
	 */
	public function cadastrarUsuario(Usuario $usuario){
		return $this->usuarioService->cadastrar($usuario);
	}
	
	/**
	 * @param Usuario $usuario
	 * @return Usuario
	 */
	public function alterarUsuario(Usuario $usuario){
		return $this->usuarioService->alterar($usuario);
	}
	
	/**
	 * @param Usuario $usuario
	 * @return \modelo\entidades\Usuario
	 */
	public function buscarUsuario(Usuario $usuario){
		return $this->usuarioService->buscar($usuario);
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listarUsuarios(){
		return $this->usuarioService->listar();
	}
	
	/**
	 * @param Usuario $usuario
	 */
	public function excluirUsuario(Usuario $usuario){
		return $this->usuarioService->excluir($usuario);
	}
	
	/**
	 * @param Permissao $permissao
	 */
	public function excluirPermissao(Permissao $permissao){
		return $this->permissaoService->excluirPermissao($permissao);
	}
	
	public function excluirPerfil(Perfil $perfil) {
		$this->perfilService->excluir($perfil);
	}
	
	/**
	 * Autentica para acesso ao sistema
	 * @param Usuario $usuario
	 * @return Usuario
	 */
	public function autenticar(Usuario $usuario) {
		return $this->usuarioService->autenticar($usuario);
	}
	
	/**
	 * @param Usuario $usuario
	 * @return Usuario
	 */
	public function alterarDadosCadastrais(Usuario $usuario) {
		return $this->usuarioService->alterarDadosCadastrais($usuario);
	}
	
	/**
	 * @param Perfil $perfil
	 * @return array Permissao
	 */
	public function getPermissoesPerfil(Perfil $perfil) {
		return $this->permissaoService->getPermissoesPerfil($perfil);
	}
	
	/**
	 * @param Usuario $usuario
	 */
	public function enviarEmailEsqueciSenha(Usuario $usuario) {
		$this->usuarioService->enviarEmailEsqueciSenha($usuario);
	}
	
	/**
	 * @param Cliente $cliente
	 * @return \modelo\entidades\Cliente
	 */
	public function inserirCliente(Cliente $cliente) {
		return $this->clienteService->inserirCliente($cliente);
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listarTodosClientes() {
		return $this->clienteService->listarTodosClientes();
	}
	
	/**
	 * @param Cliente $cliente
	 * @return \modelo\entidades\Cliente
	 */
	public function buscarCliente(Cliente $cliente) {
		return $this->clienteService->buscarCliente($cliente);
	}
	
	/**
	 * @param Cliente $cliente
	 * @return \modelo\entidades\Cliente
	 */
	public function atualizarCliente(Cliente $cliente) {
		return $this->clienteService->atualizarCliente($cliente);
	}
	
	/**
	 * @param Cliente $cliente
	 */
	public function excluirCliente(Cliente $cliente) {
		$this->clienteService->excluir($cliente);
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listarTodasCategoriasOS() {
		return $this->categoriaOSService->listarTodas();
	}
	
	/**
	 * @param CategoriaOS $categoriaOS
	 */
	public function inserirCategoriasOS(CategoriaOS $categoriaOS){
		return $this->categoriaOSService->inserir($categoriaOS);
	}
	
	/**
	 * @param CategoriaOS $categoriaOS
	 * @return \modelo\entidades\CategoriaOS
	 */
	public function atualizarCategoriasOS(CategoriaOS $categoriaOS){
		return $this->categoriaOSService->atualizar($categoriaOS);
	}
	
	/**
	 * @param CategoriaOS $categoriaOS
	 * @return \modelo\dao\Perfil
	 */
	public function buscarCategoriaOS(CategoriaOS $categoriaOS) {
		return $this->categoriaOSService->buscar($categoriaOS);
	}
	
	/**
	 * 
	 * @param CategoriaOS $categoriaOS
	 */
	public function excluirCategoriaOS(CategoriaOS $categoriaOS) {
		$this->categoriaOSService->excluir($categoriaOS);
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listarProdutos(){
		return $this->produtoService->listarProdutos();
	}
	
	/**
	 * @param Produto $produto
	 */
	public function inserirProduto(Produto $produto){
		$this->produtoService->inserir($produto);
	}
	
	/**
	 * @param Produto $produto
	 */
	public function atualizarProduto(Produto $produto){
		$this->produtoService->atualizar($produto);
	}
	
	/**
	 * @param Produto $produto
	 * @return \modelo\entidades\Produto
	 */
	public function buscarProduto(Produto $produto){
		return $this->produtoService->buscar($produto);
	}
	
	/**
	 * @param Produto $produto
	 */
	public function excluirProduto(Produto $produto){
		$this->produtoService->excluir($produto);
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listarCategoriasOSAtivas() {
		return $this->categoriaOSService->listarAtivas();
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listarOrdensServico(){
		return $this->ordemServicoService->listar();
	}
	
	/**
	 * @param OrdemServico $ordemServico
	 */
	public function inserirOrdemServico(OrdemServico $ordemServico){
		$this->ordemServicoService->inserir($ordemServico);
	}
	
	/**
	 * @param OrdemServico $ordemServico
	 */
	public function atualizarOrdemServico(OrdemServico $ordemServico){
		$this->ordemServicoService->atualizar($ordemServico);
	}
	
	/**
	 * @param OrdemServico $ordemServico
	 * @return \modelo\entidades\OrdemServico
	 */
	public function buscarOrdemServico(OrdemServico $ordemServico){
		return $this->ordemServicoService->buscar($ordemServico);
	}
	
	/**
	 * @param OrdemServico $ordemServico
	 */
	public function excluirOrdemServico(OrdemServico $ordemServico){
		$this->ordemServicoService->excluir($ordemServico);
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listarOurives(){
		return $this->usuarioService->listarOurives();
	}
	
	/**
	 * @param ImagemOS $imagemOS
	 * @return \modelo\entidades\ImagemOS
	 */
	public function buscarImagemOS(ImagemOS $imagemOS){
		return $this->imagemOSService->buscar($imagemOS);
	}
	
	/**
	 * @param ImagemOS $imagemOS
	 */
	public function excluirImagemOS(ImagemOS $imagemOS){
		$this->imagemOSService->excluir($imagemOS);
	}
	
}

?>