To create pdf files from the markdown files, first ensure that your system have installed the following: 
* `mermaid-cli`, refer to their [documentation on how to install](https://github.com/mermaid-js/mermaid-cli)
* `pandoc`, refer to their [documentation on how to install](https://pandoc.org/installing.html)

---

To create a pdf of the [Case markdown](/Documentation/Case.md) file:
1. Convert the case markdown file to pdf, this command should be run from the documentation folder: `pandoc Case.md -o Case.pdf`

To create a pdf of the [ProductRepport markdown](/Documentation/ProductRepport.md) file:
1. Convert all mermaid diagrams to png with the following command inside the Documentation folder: `mmdc -i ProductRepport.md -o ProductRepport-converted.md -e "png"`
2. Convert the newly created markdown file to pdf, this command should also be run from the documentation folder: `pandoc ProductRepport-converted.md -o ProductRepport.pdf`
