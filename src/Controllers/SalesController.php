<?php declare(strict_types=1);

namespace Bookstore\Controllers;
use Bookstore\Domain\Sale;
use Bookstore\Models\SaleModel;
use Exception;

class SalesController extends AbstractController {

    public function add(int $id): string {
        $bookId = $id;
        $salesModel = new SaleModel($this->db);

        $sale = new Sale();
        $sale->setCutomerId($this->customerId);
        $sale->addBook($bookId);

        try {
            $salesModel->create($sale);
        } catch (Exception $e) {
            $properties = ['errorMessage' => 'Error buying book'];
            $this->log->error("Error buying book {$e->getMessage()}");

            return $this->render('error.twig', $properties);
        }

        return $this->getByUser();
    }


    public function getByUser(): string {
        $salesModel = new SaleModel($this->db);
        $sales = $salesModel->getByUser($this->customerId);

        $properties = ['sales' => $sales];

        return $this->render('sales.twig', $properties);
    }

    public function get(int $id): string {
        $saleId = $id;
        $salesModel = new SaleModel($this->db);
        $sale = $salesModel->get($saleId);
        $properties = ['sale' => $sale];
        return $this->render('sale.twig', $properties);
    }
}