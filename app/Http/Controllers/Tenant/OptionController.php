<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\AverageHistory;
use App\Models\Tenant\ItemSeller;
use Modules\Suscription\Models\Tenant\SuscriptionPayment;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Quotation;
use App\Models\Tenant\Kardex;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Retention;
use App\Models\Tenant\Perception;
use App\Models\Tenant\Summary;
use App\Models\Tenant\Voided;
use Illuminate\Http\Request;
use App\Models\Tenant\Configuration;
use Modules\Expense\Models\Expense;
use Modules\Purchase\Models\PurchaseOrder;
use Modules\Finance\Models\GlobalPayment;
use Modules\Finance\Models\Income;
use Modules\Purchase\Models\PurchaseQuotation;
use Modules\Order\Models\OrderNote;
use Modules\Order\Models\OrderForm;
use Modules\Inventory\Models\{
    CostAverage,
    GuideItem,
    ItemWarehouse,
    InventoryKardex,
    InventoryTransferItem
};
use Modules\Sale\Models\SaleOpportunity;
use Modules\Sale\Models\Contract;
use Modules\Purchase\Models\FixedAssetPurchase;
use App\Models\Tenant\{
    BillOfExchange,
    BillOfExchangeDocument,
    BillOfExchangePayment,
    CashDocumentCredit,
    CashDocument,
    DocumentItem,
    InitStock,
    Inventory,
    Item,
    ItemSet,
    Promotion,
    QuotationItem,
    SaleNoteItem
};
use Exception;
use Modules\Payment\Models\PaymentLink;
use Modules\MercadoPago\Models\Transaction;
use Modules\Order\Models\OrderFormItem;
use Modules\Order\Models\OrderNoteItem;
use Modules\Pos\Models\Tip;
use Modules\Production\Models\{
    Production,
    Mill,
    Packaging,
};
use Modules\Purchase\Models\PurchaseQuotationItem;
use Modules\Restaurant\Models\Food;
use Modules\Sale\Models\ContractItem;
use Modules\Sale\Models\SaleOpportunityItem;

class OptionController extends Controller
{

    protected $delete_quantity;

    public function create()
    {
        return view('tenant.options.form');
    }
    public function delete_items(){
       
        try{
            $items_deleted = 0;
            ItemSet::query()->delete();
            Item::where('id', '>', 0)->chunk(100, function ($items) use (&$items_deleted) {
                foreach ($items as $item) {
                    ItemSet::where('item_id', $item->id)->delete();
                    Promotion::where('item_id', $item->id)->delete();
                    ContractItem::where('item_id', $item->id)->delete();
                    OrderFormItem::where('item_id', $item->id)->delete();
                    PurchaseQuotationItem::where('item_id', $item->id)->delete();
                    CostAverage::where('item_id', $item->id)->delete();
                    InitStock::where('item_id', $item->id)->delete();
                    OrderNoteItem::where('item_id', $item->id)->delete();
                    QuotationItem::where('item_id', $item->id)->delete();
                    GuideItem::where('item_id', $item->id)->delete();
                    InventoryTransferItem::query()->delete();
                    Food::where('item_id', $item->id)->delete();
                    SaleOpportunityItem::where('item_id', $item->id)->delete();
                    $items_sale_note = SaleNoteItem::where('item_id', $item->id)->get();
                    foreach ($items_sale_note as $item_sale_note) {
                        
                        $item_sale_note->sellers()->delete();
                    }
                    SaleNoteItem::where('item_id', $item->id)->delete();
                    // $item->sale_note_items()->delete();
                    $item_lots = $item->item_lots();
                    foreach ($item_lots as $item_lot) {
                        InventoryTransferItem::where('item_lot_id', $item_lot->id)->delete();
                    }
                    $item->item_lots()->delete();

                    $item->item_unit_types()->delete();
                    $item->dispatch_items()->delete();
                    // $item->inventory_kardex()->delete();
                    $item->kardex()->delete();
                    $item->cat_digemid()->delete();
                    $item->purchase_item()->delete();
                    $item->warehouses()->delete();
                    // $item->guide_item()->delete();
                    $item->tags()->delete();
            
                    $item->sets()->delete();
                    $item->item_lots()->delete();
                    $item->images()->delete();
                    $item->lots_group()->delete();
                    $items_document = DocumentItem::where('item_id', $item->id)->get();
                    foreach ($items_document as $item_document) {
                        $item_document->sellers()->delete();
                    }
                    $item->document_items()->delete();
                    DocumentItem::where('item_id', $item->id)->delete();
                    $items_sale_note = $item->sale_note_items();
                    foreach ($items_sale_note as $item_sale_note) {
                        $item_sale_note->sellers()->delete();
                    }
                    $item->sale_note_items()->delete();
                    $item->warehousePrices()->delete();
                    $item->supplies_items()->delete();
                    $item->item_movement_rel_extra()->delete();
                    $item->technical_service_item()->delete();
                    $item->supplies()->delete();
                    InventoryKardex::where('item_id', $item->id)->chunk(100,function($row){
                        foreach ($row as $key => $value) {
                            SaleNoteItem::where('inventory_kardex_id', $value->id)->delete();
                        }
                    });
    
                    Inventory::where('item_id', $item->id)->delete();
                    $item->delete();
                    $items_deleted++;
                  
                }
            });
            InventoryKardex::query()->delete();
            return [
                'success' => true,
                'message' => 'Items eliminados',
            ];
            

        }catch(Exception $e){
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }





    }
    public function deleteDocuments(Request $request)
    {
        BillOfExchangePayment::query()->delete();
        BillOfExchangeDocument::query()->delete();
        BillOfExchange::query()->delete();
        $this->delete_quantity = 0;
        AverageHistory::query()->delete();
        SuscriptionPayment::query()->delete();
        ItemSeller::query()->delete();
        Summary::where('soap_type_id', '01')->delete();
        Voided::where('soap_type_id', '01')->delete();
        //Purchase
        $this->deleteInventoryKardex(Purchase::class);
        Purchase::where('soap_type_id', '01')->delete();
        PurchaseOrder::where('soap_type_id', '01')->delete();
        PurchaseQuotation::where('soap_type_id', '01')->delete();
        $quantity = Document::where('soap_type_id', '01')->count();
        //Document
        $this->deleteInventoryKardex(Document::class);
        Document::where('soap_type_id', '01')
        ->whereIn('document_type_id', ['07', '08'])->delete();

        $this->deleteRecordsCash(Document::class);
        // Document::where('soap_type_id', '01')->delete();

        $this->update_quantity_documents($quantity);

        Retention::where('soap_type_id', '01')->delete();
        Perception::where('soap_type_id', '01')->delete();

        //SaleNote
        $sale_notes = SaleNote::where('soap_type_id', '01')->get();
        // SaleNote::where('soap_type_id', '01')->delete();

        $this->deleteRecordsCash(SaleNote::class);

        $this->deleteInventoryKardex(SaleNote::class, $sale_notes);


        Contract::where('soap_type_id', '01')->delete();
        // Quotation::where('soap_type_id', '01')->delete();
        $this->deleteQuotation();

        SaleOpportunity::where('soap_type_id', '01')->delete();

        Expense::where('soap_type_id', '01')->delete();
        OrderNote::where('soap_type_id', '01')->delete();
        OrderForm::where('soap_type_id', '01')->delete();

        GlobalPayment::where('soap_type_id', '01')->delete();
        Tip::where('soap_type_id', '01')->delete();

        Income::where('soap_type_id', '01')->delete();

        FixedAssetPurchase::where('soap_type_id', '01')->delete();

        $this->updateStockAfterDelete();

        $this->deletePaymentLink();

        // produccion

        Production::where('soap_type_id', '01')->delete();
        Packaging::where('soap_type_id', '01')->delete();
        $this->deleteMill();

        return [
            'success' => true,
            'message' => 'Documentos de prueba eliminados',
            'delete_quantity' => $this->delete_quantity,
        ];
    }


    /**
     *
     * Eliminar links de pago y transacciones asociadas en demo
     *
     * @return void
     */
    private function deletePaymentLink()
    {
        $transactions = Transaction::where('soap_type_id', '01')->get();

        foreach ($transactions as $transaction)
        {
            $transaction->transaction_queries()->delete();
            $transaction->delete();
        }

        PaymentLink::where('soap_type_id', '01')->delete();
    }


    /**
     *
     * Eliminar registros de ingresos de insumos
     *
     * @return void
     */
    private function deleteMill()
    {
        $mills = Mill::where('soap_type_id', '01')->get();

        foreach ($mills as $mill)
        {
            $mill->relation_mill_items()->delete();
            $mill->delete();
        }

    }

    /**
     *
     * Eliminar registros relacionados en caja y cotizaciones
     *
     * @return void
     */
    private function deleteQuotation()
    {
        $records_id = Quotation::where('soap_type_id', '01')->whereFilterWithOutRelations()->select('id')->get()->pluck('id')->toArray();
        // dd($records_id);
        CashDocument::whereIn('quotation_id', $records_id)->delete();
        Quotation::where('soap_type_id', '01')->delete();
    }


    /**
     *
     * Eliminar registros relacionados en caja - notas de venta/cpe
     *
     * @return void
     */
    private function deleteRecordsCash($model)
    {
        $records_id = $model::where('soap_type_id', '01')->whereFilterWithOutRelations()->select('id')->get()->pluck('id')->toArray();

        $column = ($model === Document::class) ? 'document_id' : 'sale_note_id';

        CashDocumentCredit::whereIn($column, $records_id)->delete();

        $model::where('soap_type_id', '01')->delete();
    }


    private function deleteInventoryKardex($model, $records = null){

        if(!$records){
            $records = $model::where('soap_type_id', '01')->get();
        }

        $this->delete_quantity += $records->count();

        foreach ($records as $record) {

            $record->inventory_kardex()->delete();

        }
    }

    private function updateStockAfterDelete(){

        // if($this->delete_quantity > 0){

        //     ItemWarehouse::latest()->update([
        //         'stock' => 0
        //     ]);

        // }

    }

    private function update_quantity_documents($quantity)
    {
        $configuration = Configuration::first();
        $configuration->quantity_documents -= $quantity;
        $configuration->save();
    }

}