import {Injectable} from '@angular/core';
import {RequestService} from './request.service';
import { environment } from '../../../../environments/environment';
import { FilterObject } from '../../models/filter-object';
import { BaseModel } from '../../models/baseModel';
import {Observable} from 'rxjs';


@Injectable()
export class CrudService {
    constructor( private _requestService: RequestService) { }

    getList <T extends BaseModel> (ControllerName: string): Observable<T[]> {
		return this._requestService.SendRequest('GET', environment.apiBaseURL + ControllerName, null, null);
    }
    getPaginatedList <T extends BaseModel>(ControllerName: string, filterObject: FilterObject): Observable<T> {
		filterObject.PageSize = environment.pageSize;
        return this._requestService.SendRequest('POST', environment.apiBaseURL + ControllerName + '/filtered-list', filterObject, null);
    }
    get <T extends BaseModel> (ControllerName: string, id: number): Observable<T> {
        return this._requestService.SendRequest('GET', environment.apiBaseURL + ControllerName + '/' + id, null, null);
    }
    add <T extends BaseModel>(ControllerName: string, addedObject: Object): Observable<T[]> {
        return this._requestService.SendRequest('POST', environment.apiBaseURL + ControllerName, addedObject, null);
    }
    edit <T extends BaseModel>(ControllerName: string, editedObject: Object, id: number): Observable<T> {
        return this._requestService.SendRequest('PUT', environment.apiBaseURL + ControllerName + '/' + id, editedObject, null);
    }
    delete <T extends BaseModel> (ControllerName: string, id: number): Observable<T[]>  {
        return this._requestService.SendRequest('DELETE', environment.apiBaseURL + ControllerName + '/' + id, null, null);
    }
}
