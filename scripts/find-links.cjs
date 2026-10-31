async function find() {
    const res = await fetch('https://demo.gustiswandana.space');
    const text = await res.text();
    const matches = text.match(/https:\/\/demo\.gustiswandana\.space\/belajar\/[a-zA-Z0-9\-_/]+/g) || [];
    console.log('Classroom links found:', [...new Set(matches)]);

    const courseLinks = text.match(/https:\/\/demo\.gustiswandana\.space\/admin\/courses\/[a-zA-Z0-9\-_/]+/g) || [];
    console.log('Admin course links found:', [...new Set(courseLinks)]);
}
find();

