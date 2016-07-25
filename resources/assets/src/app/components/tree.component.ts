import {Component} from '@angular/core';
import {TreeComponent as BaseTreeComponent} from 'angular2-tree-component';

@Component({
    selector: 'cx-tree',
    templateUrl: 'app/views/tree.html',
    directives: [BaseTreeComponent]
})
export class TreeComponent {
    nodes = [
        {
            name: 'root1',
            children: [
                { name: 'child1' },
                { name: 'child2' }
            ]
        },
        {
            name: 'root2',
            children: [
                { name: 'child2.1' },
                {
                    name: 'child2.2',
                    children: [
                        { name: 'subsub' }
                    ]
                }
            ]
        }
    ];
}

