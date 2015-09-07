<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Panier
 *
 * @author Mehrez
 */

namespace MyApp\FrontofficeBundle\Services;

class Panier {

    private $items;
    private $total;

    public function __construct() {
//      parent::__construct();
//      session_start();
        $this->items = array();
        /* if (!isset($_SESSION['cart'])) 
          {$this->items = array(); }
          else
          { $this->items = unserialize($_SESSION['cart']); } */
    }

    public function additem($id) {
        if (array_key_exists($id, $this->items)) {
            $this->items[$id] ++;
        } else {
            $this->items[$id] = 1;
        }
    }

    public function delitem($id) {
        if (array_key_exists($id, $this->items)) {
            if ($this->items[$id] > 1) {
                $this->items[$id] --;
            }
        } else {
            unset($this->items[$id]);
        }
    }

    public function emptycart() {
        $this->items = array();
    }

    public function nbitems() {
        return array_sum($this->items);
    }

    public function viewcart() {
        return $this->items;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function totalcart() {
        $this->total = 0;

        foreach ($this->items as $id => $qty) {
            $this->total += $this->productprice($id) * $qty;
        }

        return $this->total;
    }

    public function __destruct() {
        $_SESSION['cart'] = serialize($this->items);
    }

}
