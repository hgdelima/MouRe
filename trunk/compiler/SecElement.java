
public class SecElement {
	public String data;

	public SecElement() {	this("");	}
	public SecElement(String line) {	data=line;	}

	public void append(String line){	data=data+line;	}
	public String toString(){	return " ["+data+"] ";	}
}
