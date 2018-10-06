<?php


Route::get('/', 'BittrexExchange\WebsiteController@landingPage');

Route::get('/test', 'BittrexExchange\TestController@test');

Route::get('/zohoverify/verifyforzoho.html', 'BittrexExchange\TestController@test');


Route::group(['middleware' => ['admin_logged', 'can_see']], function ()
{
    Route::get('clients/reset/sell/limit/orders/all/clients', 'BittrexExchange\Clients\ClientsUtilitiesController@resetSellLimitOrdersByXFactorForAllClientsEdit');
    Route::patch('clients/reset/all/sell/limit/orders/all/clients', 'BittrexExchange\Clients\ClientsUtilitiesController@resetSellLimitOrdersByXFactorForAllClientsUpdate');

    Route::get('clients/reset/specific/sell/limit/orders', 'BittrexExchange\Clients\ClientsUtilitiesController@resetSpecificSellLimitOrdersEdit');
    Route::patch('clients/reset/specific/sell/limit/orders', 'BittrexExchange\Clients\ClientsUtilitiesController@resetSpecificSellLimitOrdersUpdate');

    Route::get('clients/sell/all/altcoins/single/client', 'BittrexExchange\Clients\ClientsUtilitiesController@sellAllAltcoinsOfSingleClientEdit');
    Route::patch('clients/sell/all/altcoins/single/client', 'BittrexExchange\Clients\ClientsUtilitiesController@sellAllAltcoinsOfSingleClientUpdate');

    Route::get('clients/buy/for/all/clients', 'BittrexExchange\Clients\ClientsUtilitiesController@buyForAllClientsEdit');
    Route::patch('clients/buy/for/all/clients', 'BittrexExchange\Clients\ClientsUtilitiesController@buyForAllClients');
    Route::get('clients/cancel/buy/for/all/clients', 'BittrexExchange\Clients\ClientsUtilitiesController@cancelBuyForAllClientsEdit');
    Route::patch('clients/cancel//buy/for/all/clients', 'BittrexExchange\Clients\ClientsUtilitiesController@cancelBuyForAllClients');

    Route::get('clients/sell/for/all/clients', 'BittrexExchange\Clients\ClientsUtilitiesController@sellForAllClientsEdit');
    Route::patch('clients/sell/for/all/clients', 'BittrexExchange\Clients\ClientsUtilitiesController@sellForAllClients');
    Route::get('clients/cancel/sell/for/all/clients', 'BittrexExchange\Clients\ClientsUtilitiesController@cancelSellForAllClientsEdit');
    Route::patch('clients/cancel/sell/for/all/clients', 'BittrexExchange\Clients\ClientsUtilitiesController@cancelSellForAllClients');

    Route::get('clients/buy/tether/for/all/clients', 'BittrexExchange\Clients\USDTMarketController@buyTetherEdit');
    Route::patch('clients/buy/tether/for/all/clients', 'BittrexExchange\Clients\USDTMarketController@buyTether');
    Route::get('clients/cancel/buy/tether/for/all/clients', 'BittrexExchange\Clients\USDTMarketController@cancelBuyTetherEdit');
    Route::patch('clients/cancel/buy/tether/for/all/clients', 'BittrexExchange\Clients\USDTMarketController@cancelBuyTether');

    Route::get('clients/sell/tether/for/all/clients', 'BittrexExchange\Clients\USDTMarketController@sellTetherEdit');
    Route::patch('clients/sell/tether/for/all/clients', 'BittrexExchange\Clients\USDTMarketController@sellTether');
    Route::get('clients/cancel/sell/tether/for/all/clients', 'BittrexExchange\Clients\USDTMarketController@cancelSellTetherEdit');
    Route::patch('clients/cancel/sell/tether/for/all/clients', 'BittrexExchange\Clients\USDTMarketController@cancelSellTether');

    Route::get('clients/pauseallclients', 'BittrexExchange\Clients\ClientsUtilitiesController@pauseAllAccounts');

    Route::get('clients/exchange/{id?}', 'BittrexExchange\Clients\ExchangeController@index');
    Route::get('clients/exchange/{clientId?}/altcoin/order/history/{marketName?}', 'BittrexExchange\Clients\AltcoinOrderHistoryController@index');
    Route::post('clients/exchange/buylimit', 'BittrexExchange\Clients\ExchangeController@buyLimit');
    Route::post('clients/exchange/selllimit', 'BittrexExchange\Clients\ExchangeController@sellLimit');

    Route::get('clients/{clientId?}/crypto/balance', 'BittrexExchange\CryptoCurrenciesBalanceController@index');

    Route::get('bittrex/koinex/arbitrage/overview', 'BittrexKoinexArbitrage\ArbitrageOverviewController@index');
    Route::get('bittrex/koinex/arbitrage/fake/koinex/markets/volume', 'BittrexKoinexArbitrage\ArbitrageOverviewController@fakeKoinexMarketQuantities');
    Route::get('bittrex/koinex/arbitrage/json/data', 'BittrexKoinexArbitrage\ArbitrageOverviewController@viewJsonData');
    Route::get('bittrex/koinex/arbitrage/opportunities', 'BittrexKoinexArbitrage\ArbitrageOpportunitiesController@index');
    Route::get('bittrex/koinex/arbitrage/remove/negative/returns', 'BittrexKoinexArbitrage\ArbitrageOpportunitiesController@removeNegativeReturns');
    Route::get('bittrex/indian/exchanges/arbitrage/opportunities', 'IndianExchangesArbitrage\IndianExchangesArbitrageOpportunitiesController@index');

    Route::get('bittrex/koinex/arbitrage/koinex/orderbook', 'BittrexKoinexArbitrage\ArbitrageOverviewController@koinexOrderBook');
    Route::post('bittrex/koinex/arbitrage/koinex/orderbook/bcc/buy/quantity', 'BittrexKoinexArbitrage\ArbitrageOverviewController@bccBuyVolumeOrderBook');
    Route::post('bittrex/koinex/arbitrage/koinex/orderbook/eth/buy/quantity', 'BittrexKoinexArbitrage\ArbitrageOverviewController@ethBuyVolumeOrderBook');
    Route::post('bittrex/koinex/arbitrage/koinex/orderbook/ltc/buy/quantity', 'BittrexKoinexArbitrage\ArbitrageOverviewController@ltcBuyVolumeOrderBook');
    Route::post('bittrex/koinex/arbitrage/koinex/orderbook/xrp/buy/quantity', 'BittrexKoinexArbitrage\ArbitrageOverviewController@xrpBuyVolumeOrderBook');
    Route::post('bittrex/koinex/arbitrage/koinex/orderbook/btc/buy/quantity', 'BittrexKoinexArbitrage\ArbitrageOverviewController@btcBuyVolumeOrderBook');

    Route::post('bittrex/koinex/arbitrage/koinex/orderbook/bcc/sell/quantity', 'BittrexKoinexArbitrage\ArbitrageOverviewController@bccSellVolumeOrderBook');
    Route::post('bittrex/koinex/arbitrage/koinex/orderbook/eth/sell/quantity', 'BittrexKoinexArbitrage\ArbitrageOverviewController@ethSellVolumeOrderBook');
    Route::post('bittrex/koinex/arbitrage/koinex/orderbook/ltc/sell/quantity', 'BittrexKoinexArbitrage\ArbitrageOverviewController@ltcSellVolumeOrderBook');
    Route::post('bittrex/koinex/arbitrage/koinex/orderbook/xrp/sell/quantity', 'BittrexKoinexArbitrage\ArbitrageOverviewController@xrpSellVolumeOrderBook');
    Route::post('bittrex/koinex/arbitrage/koinex/orderbook/btc/sell/quantity', 'BittrexKoinexArbitrage\ArbitrageOverviewController@btcSellVolumeOrderBook');

    Route::get('bcc/koinex/up/bittrex/down', 'BittrexKoinexArbitrage\BCCKoinexUpBittrexDownController@index');
    Route::get('bcc/bittrex/up/koinex/down', 'BittrexKoinexArbitrage\BCCBittrexUpKoinexDownController@index');

    Route::get('eth/koinex/up/bittrex/down', 'BittrexKoinexArbitrage\ETHKoinexUpBittrexDownController@index');
    Route::get('eth/bittrex/up/koinex/down', 'BittrexKoinexArbitrage\ETHBittrexUpKoinexDownController@index');

    Route::get('ltc/koinex/up/bittrex/down', 'BittrexKoinexArbitrage\LTCKoinexUpBittrexDownController@index');
    Route::get('ltc/bittrex/up/koinex/down', 'BittrexKoinexArbitrage\LTCBittrexUpKoinexDownController@index');

    Route::get('xrp/koinex/up/bittrex/down', 'BittrexKoinexArbitrage\XRPKoinexUpBittrexDownController@index');
    Route::get('xrp/bittrex/up/koinex/down', 'BittrexKoinexArbitrage\XRPBittrexUpKoinexDownController@index');

    Route::get('kpas/koinex/arbitrage/buy/on/bittrex/{marketName?}/{rate?}/{quantity?}', 'BittrexKoinexArbitrage\ArbitrageOverviewController@buyOnBittrex');
    Route::get('kpas/koinex/arbitrage/sell/on/bittrex/{marketName?}/{rate?}/{quantity?}', 'BittrexKoinexArbitrage\ArbitrageOverviewController@sellOnBittrex');
    Route::get('kpas/crypto/balance', 'BittrexKoinexArbitrage\BittrexAccountBalanceController@index');

    Route::get('clients/{clientId?}/btc/balance/history', 'BittrexExchange\BalanceHistoryController@index');
    Route::get('clients/{clientId?}/order/history', 'BittrexExchange\OrderHistoryController@index');
    Route::get('clients/{clientId?}/deposits/withdrawals', 'BittrexExchange\Clients\DepositsAndWithdrawalsController@index');

    Route::get('clients/payment/{clientId?}', 'BittrexExchange\Clients\PaymentReceiptController@index');
    Route::get('clients/payment/{clientId?}/create', 'BittrexExchange\Clients\PaymentReceiptController@create');
    Route::post('clients/payment/create', 'BittrexExchange\Clients\PaymentReceiptController@store');
    Route::get('clients/payment/{clientId?}/{paymentId?}/edit', 'BittrexExchange\Clients\PaymentReceiptController@edit');
    Route::post('clients/payment/update', 'BittrexExchange\Clients\PaymentReceiptController@update');

    Route::get('btc/index', 'BittrexExchange\BittrexBTCIndexController@index');
    Route::get('past/market/statistics', 'BittrexExchange\PastMarketStatisticsController@index');
    Route::get('market/pumps', 'BittrexExchange\MarketPumpsController@index');
    Route::get('market/dumps', 'BittrexExchange\MarketDumpsController@index');
    Route::get('market/listing', 'BittrexExchange\MarketListingController@index');
    Route::get('market/delisting', 'BittrexExchange\MarketDelistingController@index');



    Route::get('arbitrage/opportunities', 'BittrexExchange\BitcoinArbitrageOpportunitiesController@index');
    Route::get('reverse/arbitrage/opportunities', 'BittrexExchange\BitcoinReverseArbitrageOpportunitiesController@index');
    Route::get('trading/rules', 'BittrexExchange\TradingRulesController@index');
    Route::get('bot/status', 'BittrexExchange\BotRunningStatusController@index');
    Route::get('odds/altcoin/chart/{marketName?}', 'BittrexExchange\AltcoinChartController@index');

    Route::resource('clients', 'BittrexExchange\Clients\ClientsListController');
    Route::resource('odds', 'BittrexExchange\MarketOddsController');
    Route::resource('support', 'BittrexExchange\SupportResistanceController');
    Route::resource('altcoinssettings', 'BittrexExchange\AltcoinsSettingsController');
    Route::resource('opportunities', 'BittrexExchange\PastInvestmentsOpportunitiesController');
    Route::resource('altcoininfo', 'BittrexExchange\AltcoinInformationController');
    Route::resource('/kbas', 'BittrexKoinexArbitrage\ArbitrageSettings\SettingsController');
    Route::resource('buysetting', 'BittrexExchange\BotSettings\BuySettingsController');
    Route::resource('sellsetting', 'BittrexExchange\BotSettings\SellSettingsController');
    Route::resource('startpausebotsetting', 'BittrexExchange\BotSettings\StartPauseBotsSettingsController');
    Route::resource('mailinglistsetting', 'BittrexExchange\BotSettings\MailingListSettingsController');
});

