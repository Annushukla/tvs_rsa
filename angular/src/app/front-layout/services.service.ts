import { Injectable } from '@angular/core';
import { HttpClient,HttpParams } from '@angular/common/http'; 
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ServicesService {

	base_url = environment.baseUrl;
	constructor(private httpClient: HttpClient) { }


	login(sendData){
	    return this.httpClient.post(this.base_url+'Admin/login',sendData);
	}

	signup(sendData){
	    return this.httpClient.post(this.base_url+'Admin/signup',sendData);
	}

	dashboard(sendData){
	    return this.httpClient.post(this.base_url+'Admin/dashboard',sendData);
	}

	generatePolicy(sendData){
	    return this.httpClient.post(this.base_url+'Admin/generatePolicy',sendData);
	}

	soldPolicies(sendData){
	    return this.httpClient.post(this.base_url+'Admin/soldPolicies',sendData);
	}

	cancelPolicies(sendData){
	    return this.httpClient.post(this.base_url+'Admin/cancelPolicies',sendData);
	}

	dealerDocuments(sendData){
	    return this.httpClient.post(this.base_url+'Admin/dealerDocuments',sendData);
	}

	faq(sendData){
	    return this.httpClient.post(this.base_url+'Admin/faq',sendData);
	}

	dealerRequestData(sendData){
	    return this.httpClient.post(this.base_url+'Admin/dealerRequestData',sendData);
	}

	SoldPoliciesSummary(sendData){
	    return this.httpClient.post(this.base_url+'Admin/SoldPoliciesSummary',sendData);
	}

	dealerBankTransaction(sendData){
	    return this.httpClient.post(this.base_url+'Admin/dealerBankTransaction',sendData);
	}

	transactionData(sendData){
	    return this.httpClient.post(this.base_url+'Admin/transactionData',sendData);
	}

	generateInvoice(sendData){
	    return this.httpClient.post(this.base_url+'Admin/generateInvoice',sendData);
	}

	gstTransaction(sendData){
	    return this.httpClient.post(this.base_url+'Admin/gstTransaction',sendData);
	}

	



}
