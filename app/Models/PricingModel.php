<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;
use App\Models\BindingModel;
use App\Models\LaminationModel;
use App\Models\CoverModel;
use App\Models\GsmModel;

class PricingModel extends Model
{
    
    protected $table = 'pricing';
    protected $fillable = ['admin_id', 'product_id', 'paper_size_id', 'paper_gsm_id', 'paper_type_id', 'side', 'color', 'other_price'];
    protected $guarded = ['id'];

    public static function defPaperSize($pId) {

        $size = 'A4';        

        $query = self::
        join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
        ->where('pricing.product_id', $pId)        
        ->select('paper_size.*')
        ->distinct('paper_size.id');

        $newQuery = $query->where('paper_size.size', $size);

        if ($newQuery->count()) {
            return $newQuery->first();
        } else {
            return self::
            join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
            ->where('pricing.product_id', $pId)        
            ->select('paper_size.*')
            ->distinct('paper_size.id')->first();
        }
        
    }

    public static function defPaperGsm($pId, $paperSizeData) {

        if (!empty($paperSizeData) && $paperSizeData->count()) {

            $paperSize = $paperSizeData->id;

            $getGsm = self::
            join('gsm', 'pricing.paper_gsm_id', '=', 'gsm.id')
            ->join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
            ->where(['pricing.product_id' => $pId, 'pricing.paper_size_id' => $paperSize])
            ->select('gsm.*', 'paper_type.paper_type as paper_type_name')
            ->distinct('gsm.id')
            ->orderBy('gsm.gsm', 'asc')
            ->get();

            $gsmId = null;

            $getBinding = BindingModel::where('paper_size_id', $paperSize)->get();
            $getLamination = LaminationModel::where('paper_size_id', $paperSize)->get();

            $gsmOptions = '<option value="">Select Paper GSM</option>';

            if (!empty($getGsm) && $getGsm->count()) {
                $i=1;
                foreach ($getGsm as $gsm) {

                    $sel = '';

                    if ($gsm->gsm == 75 && $gsm->paper_type_name == 'Normal Paper') {
                        $sel = 'selected';
                        $gsmId = $gsm->id;
                    } elseif ($i==1) {
                        $sel = 'selected';
                        $gsmId = $gsm->id;
                    }

                    $gsmOptions .= '<option '.$sel.' data-weight="'.$gsm->per_sheet_weight.'" value="'.$gsm->id.'">'.$gsm->gsm.' GSM - '.$gsm->paper_type_name.'</option>';
                    $i++;
                }
            }

            $bindingOptions = '<option value="">Select Binding</option>';

            if (!empty($getBinding) && $getBinding->count()) {
                foreach ($getBinding as $binding) {                    
                    $bindingOptions .= '<option '.$sel.' data-price="'.$binding->price.'" data-split="'.$binding->split.'" value="'.$binding->id.'">'.$binding->binding_name.'</option>';
                }
            }

            $laminationOptions = '<option value="">Select Lamination</option>';

            if (!empty($getLamination) && $getLamination->count()) {
                foreach ($getLamination as $lamination) {
                    $laminationOptions .= '<option '.$sel.' data-price="'.$lamination->price.'" value="'.$lamination->id.'">'.$lamination->lamination." - ".$lamination->lamination_type.'</option>';
                }
            }

            return (object) array(
                'gsmId' => $gsmId,           
                'gsmOptions' => $gsmOptions,
                'bindingOptions' => $bindingOptions,
                'laminationOptions' => $laminationOptions,
            );

        } else {
            return (object) array(   
                'gsmId' => null,
                'gsmOptions' => null,
                'bindingOptions' => null,
                'laminationOptions' => null,
            );
        }

    }

    public static function defPaperType($pId, $paperSizeData, $paperGsm) {

        if (!empty($paperSizeData)) {
            
            $paperSize = $paperSizeData->id;

            // $getPaperType = self::
            // join('gsm', 'pricing.paper_type_id', '=', 'gsm.paper_type')
            // ->join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
            // ->where(['pricing.product_id' => $pId, 'pricing.paper_size_id' => $paperSize, 'pricing.paper_gsm_id' => $paperGsm])
            // ->select('pricing.paper_type_id', 'paper_type.paper_type', 'gsm.paper_type_price')
            // ->distinct('gsm.id')
            // ->get();

            $getPaperType = GsmModel::
            join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
            ->select('paper_type.paper_type', 'paper_type.id as paper_type_id', 'gsm.paper_type_price')
            ->where(['gsm.paper_size' => $paperSize, 'gsm.id' => $paperGsm])->get();

            $paperTypeId = null;

            if ($getPaperType->count()) {
                
                $paperTypeOptions = '<option value="">Select Paper Type</option>';

                if (!empty($getPaperType) && $getPaperType->count()) {
                    $i=1;
                    foreach ($getPaperType as $paperType) {

                        $sel = '';
                        if ($i==1) {
                            $sel = 'selected';
                            $paperTypeId = $paperType->paper_type_id;
                        }

                        $paperTypeOptions .= '<option '.$sel.' data-price="'.$paperType->paper_type_price.'" value="'.$paperType->paper_type_id.'">'.$paperType->paper_type.'</option>';

                        $i++;
                    }
                }

                return (object) array(
                    'paperTypeId' => $paperTypeId,
                    'paperOptions' => $paperTypeOptions,
                );

            } else {
                return (object) array(
                    'paperTypeId' => $paperTypeId,
                    'paperOptions' => null,
                );
            }

        } else {
            return (object) array(
                'paperTypeId' => null,
                'paperOptions' => null,
            );
        }

    }

    public static function defPaperSides($productId, $paperSize, $paperGsm, $paperType) {

        if (!empty($paperSize)) {
            
            $getPaperSides = PricingModel::
            where(['product_id' => $productId, 'paper_size_id' => $paperSize, 'paper_gsm_id' => $paperGsm, 'paper_type_id' => $paperType])
            ->select('side')
            ->distinct('product_id')
            ->get();

            $paperSideId = null;

            if ($getPaperSides->count()) {

                $paperSideOptions = '<option value="">Select Print Sides</option>';

                if (!empty($getPaperSides) && $getPaperSides->count()) {
                    $i=1;
                    foreach ($getPaperSides as $paperSide) {
                        $sel = '';
                        if ($i==1) {
                            $sel='selected';
                            $paperSideId = $paperSide->side;
                        }
                        $paperSideOptions .= '<option '.$sel.' value="'.$paperSide->side.'">'.$paperSide->side.'</option>';
                        $i++;
                    }
                }

                return (object) array(
                    'paperSideId' => $paperSideId,
                    'paperSidesOptions' => $paperSideOptions,
                );

            } else {
                return (object) array(
                    'paperSideId' => $paperSideId,
                    'paperSidesOptions' => $paperSideOptions,
                );
            }

        } else {
            return (object) array(
                'paperSideId' => null,
                'paperSidesOptions' => null,
            );
        }
        
    }

    public static function defPaperColor($productId, $paperSize, $paperGsm, $paperType, $paperSides) {

        $getPaperColor = PricingModel::
        where(['product_id' => $productId, 'paper_size_id' => $paperSize, 'paper_gsm_id' => $paperGsm, 'paper_type_id' => $paperType, 'side' => $paperSides])
        ->select('color', 'other_price')
        ->distinct('color')
        ->get();

        $paperColorOptions = '<option value="">Select Color</option>';
        $paperColorId = null;

        if (!empty($getPaperColor) && $getPaperColor->count()) {
            $i=1;
            foreach ($getPaperColor as $color) {
                $sel = '';
                if ($i==1) {
                    $sel = 'selected';
                    $paperColorId = $color->color;
                }
                $paperColorOptions .= '<option '.$sel.' data-price="'.$color->other_price.'" value="'.$color->color.'">'.$color->color.'</option>';
                $i++;
            }
        }

        return (object) array(
            'paperColorId' => $paperColorId,
            'paperColorOptions' => $paperColorOptions,
        );
    }

}