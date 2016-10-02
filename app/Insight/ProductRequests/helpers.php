<?php

function canEdit($productRequest, $user) {

    if (! $user->hasAccess('product-requests.edit')) {
        return false;
    }

    $transitions = $productRequest->getAvailableTransitions();
    foreach (array_keys($transitions) as $transition) {
        if ($user->hasAccess('product-requests.' . $transition)) {
            return true;
        }
    }

    return false;
}

function transitionButtonColor($option)
{
    switch ($option) {
    case "save_draft":
    case "save_reviewing":
    case "save_sourcing":
    case "save_pending_proposal":
    case "save_drafting_proposal":
    case "save_pending_approval":
    case "update":
        return 'info';
        break;
    case "submit_request":
    case "submit_for_sourcing":
    case "submit_for_pricing":
    case "create_proposal":
    case "create_additional_proposal":
    case "submit_proposal":
    case "approve":
    case "complete":
        return 'success';
        break;
    case "reassign_to_requester":
    case "revert_for_review":
    case "revert_for_cataloguing":
    case "recall_proposal":
        return 'orange';
        break;
    case "reject":
        return 'danger';
        break;
    case "delete_draft":
    case "close":
        return 'black';
        break;
    default:
        return 'info';
    }
}

function statusColor($status, $default = true)
{
    switch ($status) {
    case "DRA":
    case "INP":
        return 'default';
        break;
    case "CLS":
        return 'black';
        break;
    case "APR":
    case "COM":
        return 'success';
        break;
    default:
        return $default ? 'info' : '';
    }
}

function priceResult($product_proposal, $product_request)
{

    if (isset($product_proposal->price) && !isset($product_request->current_price)) {
        return 'New Price';
    }
    if (!isset($product_proposal->price) || !isset($product_request->current_price)) {
        return 'N/A';
    }

    $newPrice = (float)$product_proposal->price;
    $currentPrice = (float)str_replace(',', '', $product_request->current_price);

    return $newPrice < $currentPrice  ? 'Decrease' : ($newPrice > $currentPrice ? 'Increase' : 'Match');
}

function pricePercentage($product_proposal, $product_request)
{
    if (isset($product_proposal->price) && !isset($product_request->current_price)) {
        return false;
    }
    if (!isset($product_proposal->price) || !isset($product_request->current_price)) {
        return false;
    }
    $newPrice = (float)$product_proposal->price;
    $currentPrice = (float)str_replace(',', '', $product_request->current_price);

    return $newPrice < $currentPrice  ? number_format(($currentPrice - $newPrice) / $currentPrice * 100,1) . ' %'  : ($newPrice > $currentPrice ? number_format(($newPrice - $currentPrice) / $newPrice * 100,1) . ' %' : '');
}

function priceResultIcon($product_proposal, $product_request)
{
    $result = priceResult($product_proposal, $product_request);

    switch ($result) {
        case "Increase":
            return 'entypo-up';
            break;
        case "Decrease":
            return 'entypo-down';
            break;
        default:
            return 'info';
    }
}