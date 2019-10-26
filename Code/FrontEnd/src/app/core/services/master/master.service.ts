import { Injectable } from '@angular/core';
import { RequestService } from '../shared/request.service';
import { environment } from '../../../../environments/environment';
import { BaseModel } from '../../models/baseModel';
import { Observable, of } from 'rxjs';

@Injectable()
export class MasterService {
	constructor(
		private _requestService: RequestService) {
	}
	mastersList<T extends BaseModel>() {
		return this._requestService.SendRequest
			('POST', environment.apiBaseURL + 'masters/filtered-list', null, null);
	}

	masterAutoComplete(text: string) {
		if (text === '') {
			return of([]);
		}
		const model = { name: text};
		return this._requestService.SendRequest('POST', environment.apiBaseURL + 'admin/masters/auto-complete', model, null);
	}

	updateRequestStatus (statusModel: any) {
		return this._requestService.SendRequest('POST', environment.apiBaseURL + 'admin/master/request/update-status', statusModel, null);
	}
}
