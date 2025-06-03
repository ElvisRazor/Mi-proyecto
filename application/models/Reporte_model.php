<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function getVentasPorFechas($fechaInicio = null, $fechaFin = null) {
        $this->db->select('v.idVenta, v.numComprobante, v.fechaRegistro AS fecha, c.nombre AS cliente, p.nombre AS producto, SUM(dv.cantidad) AS cantidad_productos, SUM(dv.cantidad * dv.precioVenta) AS total_venta');
        $this->db->from('venta v');
        $this->db->join('detalleVenta dv', 'dv.idVenta = v.idVenta');
        $this->db->join('cliente c', 'c.idCliente = v.idCliente');
        $this->db->join('producto p', 'p.idProducto = dv.idProducto');
    
        // Aplicar el filtro de fechas si se especifican
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $this->db->where('v.fechaRegistro >=', date('Y-m-d', strtotime($fechaInicio)));
            $this->db->where('v.fechaRegistro <=', date('Y-m-d', strtotime($fechaFin)));
        }
    
        $this->db->where('v.estado', 1); // Solo ventas válidas
        $this->db->group_by('v.idVenta, v.fechaRegistro, c.nombre, p.nombre');
        $this->db->order_by('v.fechaRegistro', 'ASC');
        $ventas = $this->db->get()->result();
    
        // Calcular el total general de ventas
        $this->db->select('SUM(dv.cantidad * dv.precioVenta) AS total_general');
        $this->db->from('venta v');
        $this->db->join('detalleVenta dv', 'dv.idVenta = v.idVenta');
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $this->db->where('v.fechaRegistro >=', date('Y-m-d', strtotime($fechaInicio)));
            $this->db->where('v.fechaRegistro <=', date('Y-m-d', strtotime($fechaFin)));
        }
        $this->db->where('v.estado', 1);
        $total = $this->db->get()->row();
    
        return ['ventas' => $ventas, 'total_general' => $total->total_general];
    }

    public function getComprasPorFechas($fechaInicio = null, $fechaFin = null) {
        $this->db->select('c.idCompra, c.numComprobante, c.fechaRegistro AS fecha, p.nombre AS proveedor, pr.nombre AS producto, SUM(dc.cantidad) AS cantidad_productos, SUM(dc.cantidad * dc.precioCompra) AS total_compra');
        $this->db->from('compra c');
        $this->db->join('detalleCompra dc', 'dc.idCompra = c.idCompra');
        $this->db->join('proveedor p', 'p.idProveedor = c.idProveedor');
        $this->db->join('producto pr', 'pr.idProducto = dc.idProducto');
    
        // Aplicar el filtro de fechas si se especifican
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $this->db->where('c.fechaRegistro >=', date('Y-m-d', strtotime($fechaInicio)));
            $this->db->where('c.fechaRegistro <=', date('Y-m-d', strtotime($fechaFin)));
        }
    
        $this->db->where('c.estado', 1); // Solo compras válidas
        $this->db->group_by('c.idCompra, c.fechaRegistro, p.nombre, pr.nombre');
        $this->db->order_by('c.fechaRegistro', 'ASC');
        $compras = $this->db->get()->result();
    
        // Calcular el total general de compras
        $this->db->select('SUM(dc.cantidad * dc.precioCompra) AS total_general');
        $this->db->from('compra c');
        $this->db->join('detalleCompra dc', 'dc.idCompra = c.idCompra');
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $this->db->where('c.fechaRegistro >=', date('Y-m-d', strtotime($fechaInicio)));
            $this->db->where('c.fechaRegistro <=', date('Y-m-d', strtotime($fechaFin)));
        }
        $this->db->where('c.estado', 1);
        $total = $this->db->get()->row();
    
        return ['compras' => $compras, 'total_general' => $total->total_general];
    }    
    
    public function getClientesClasificados($fechaInicio = null, $fechaFin = null) {
        $this->db->select('c.idCliente, c.nombre, COUNT(v.idVenta) AS total_compras, SUM(dv.cantidad * dv.precioVenta) AS total_gasto');
        $this->db->from('cliente c');
        $this->db->join('venta v', 'v.idCliente = c.idCliente');
        $this->db->join('detalleVenta dv', 'dv.idVenta = v.idVenta');

        // Si se especifican fechas, aplicamos el filtro
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $this->db->where('v.fechaRegistro >=', date('Y-m-d', strtotime($fechaInicio)));
            $this->db->where('v.fechaRegistro <=', date('Y-m-d', strtotime($fechaFin)));
        }

        $this->db->where('v.estado', 1); // Solo ventas válidas
        $this->db->group_by('c.idCliente, c.nombre');
        $this->db->order_by('total_gasto', 'DESC'); // Ordenar por gasto total

        $clientes = $this->db->get()->result();

        // Clasificar clientes
        foreach ($clientes as $cliente) {
            if ($cliente->total_compras >= 10 || $cliente->total_gasto >= 5000) {
                $cliente->clasificacion = 'Fiel';
            } elseif ($cliente->total_compras >= 5 || $cliente->total_gasto >= 2000) {
                $cliente->clasificacion = 'Frecuente';
            } elseif ($cliente->total_compras >= 2 || $cliente->total_gasto >= 500) {
                $cliente->clasificacion = 'Regular';
            } else {
                $cliente->clasificacion = 'Poco Frecuente';
            }
        }
        return $clientes;
    }
}