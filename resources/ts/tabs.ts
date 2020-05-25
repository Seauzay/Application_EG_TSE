function addTab(name: string): void {
    console.log(name);
}

function showTab(n: number): void {
    // @ts-ignore
    $('#nav-tab a:nth-child(1)').tab('show')
}

export {addTab, showTab}