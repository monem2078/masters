import { BaseModel } from './baseModel';
export class Module extends BaseModel {
    id: number;
    module_name: string;
	module_name_ar: string;
	rights: Array<Object>;

}


