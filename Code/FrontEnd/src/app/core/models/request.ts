import { BaseModel } from './baseModel';

export class Request extends BaseModel {
    request_id: number;
    name: string;
    request_status_type_name: string;
    created_diff: string;
    request_status_type_id: number;
}
