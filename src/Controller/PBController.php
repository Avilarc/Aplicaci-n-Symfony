<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Categorias;
use App\Repository\CategoriasRepository;
use App\Entity\Productos;
use App\Repository\ProductosRepository;
use App\Entity\Pedidos;
use App\Entity\PedidoProducto;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Security\AppAuthAuthenticator;

class PBController extends AbstractController
{
    #[Route('/p/b', name: 'app_p_b')]
    public function index(): Response
    {
        return $this->render('pb/index.html.twig', [
            'controller_name' => 'PBController',
        ]);
    }
    #[Route('/categorias', name: 'categorias')]
    public function mostrarCategorias(CategoriasRepository $CategoriasRepository) {

        $categorias= $CategoriasRepository ->findAll();
        return $this->render (('categorias.html.twig'),[
            'categorias' => $categorias
        ]); 
  
    }
    #[Route('/productos/{id}', name: 'productos')]
    public function mostrarProductos($id, ProductosRepository $ProductosRepository) {
        $productos = $ProductosRepository->findById($id);

       
        //$productos = $ProductosRepository->findAll();
        if (!$productos) {
            throw $this->createNotFoundException('Categoría no encontrada');
        }
        return $this->render (('productos.html.twig'),[
            'productos' => $productos
        ]); 
    }    
    #[Route('/anadir', name: 'anadir')]
    public function anadir(SessionInterface $session, Request $request) {
        $id = $request->request->get('id');
        $unidades = $request->request->get('unidades');
        $carrito = $session->get('carrito');
        if(is_null($carrito)){
            $carrito = array();
        }
        if(isset($carrito[$id])){
            $carrito[$id]['unidades'] += intval($unidades);
        }else{
            $carrito[$id]['unidades'] = intval($unidades);
        }
        $session->set('carrito', $carrito);
        return $this->redirectToRoute('carrito');
    }
    #[Route('/eliminar', name: 'eliminar')]
    public function eliminar(SessionInterface $session, Request $request){
        $id = $request->request->get('cod');
        $unidades = $request->request->get('unidades');
        $carrito = $session->get('carrito');
        if(is_null($carrito)){
            $carrito = array();
        }
        if(isset($carrito[$id])){
            $carrito[$id]['unidades'] -= intval($unidades);
            if($carrito[$id]['unidades'] <= 0) {
                unset($carrito[$id]);
            }
        }
        $session->set('carrito', $carrito);
        return $this->redirectToRoute('carrito');
    }
    #[Route('/carrito', name: 'carrito')]
    public function mostrarCarrito(SessionInterface $session, ManagerRegistry $doctrine, ProductosRepository $ProductosRepository){
        $productos = [];
        $carrito = $session->get('carrito');
        if(is_null($carrito)){
            $carrito = array();
            $session->set('carrito', $carrito);
        }
        foreach ($carrito as $codigo => $cantidad){
            $producto = $ProductosRepository->find((int)$codigo);
            $elem = [];
            $elem['codProd'] = $producto->getId();
            $elem['nombre'] = $producto->getNombre();
            $elem['precio'] = $producto->getPrecio(); // New field
            $elem['peso'] = $producto->getPeso();
            $elem['stock'] = $producto->getStock();
            $elem['descripcion'] = $producto->getDescripcion();
            $elem['unidades'] = implode($cantidad);
            $productos[] = $elem;
        }
        return $this->render("carrito.html.twig", array('productos'=>$productos));
    }

    #[Route('/realizarPedido', name: 'realizarPedido')]
    public function realizarPedido(SessionInterface $session, ManagerRegistry $doctrine, ProductosRepository $ProductosRepository, UserRepository $UserRepository) {
        $entityManager = $doctrine->getManager();
        $carrito = $session->get('carrito');
        if(is_null($carrito) ||count($carrito)==0){
            return $this->render("pedido.html.twig", array('error'=>1));
        }else{
            $pedido = new Pedidos();
            $pedido->setFecha(new \DateTime());
            $usuario=  $UserRepository->find($session->get('id'));
            $pedido->setUsuario($usuario);
            $entityManager->persist($pedido);
            foreach ($carrito as $codigo => $cantidad){
                $producto =  $ProductosRepository->find((int)$codigo);
                $fila = new PedidoProducto();
                $fila->setProducto($producto);
                $fila->setUnidades( implode($cantidad));
                $fila->setPedido($pedido);
                $producto->setStock($producto->getStock() - $cantidad);
                $entityManager->persist($fila);
                $entityManager->persist($producto);
            }
        }
        try {
            $entityManager->flush();
        }catch (Exception $e) {
            return $this->render("pedido.html.twig", array('error'=>2));
        }
        $productos = [];
        foreach ($carrito as $codigo => $cantidad){
            $producto =  $ProductosRepository->find((int)$codigo);
            $elem = [];
            $elem['id'] = $producto->getId();
            $elem['nombre'] = $producto->getNombre();
            $elem['precio'] = $producto->getPrecio();
            $elem['stock'] = $producto->getStock();
            $elem['descripcion'] = $producto->getDescripcion();
            $elem['unidades'] = implode($cantidad);
            $productos[] = $elem;
        }
        $session->set('carrito', array());
        return $this->render("pedido.html.twig", array('error'=>0, 'id'=>$pedido->getId(), 'productos'=> $productos));
    }

}
