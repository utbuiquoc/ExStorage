program Chuso;
uses crt;
var n,bk:longword;
a:array[0..9] of boolean;
i:longint; kt:boolean;
begin
clrscr;
        write('nhap so:'); readln(n);
        bk:=n;
        for i:=0 to 9 do a[i]:=false;
        if bk<1 then a[0]:=true
        else
        while bk>0 do
                begin
                a[bk mod 10]:=true;
                bk:=bk div 10;
                end;
        kt:=true;
        if a[0] then kt:=false;
        for i:= 1 to 9 do if a[i] and (n mod i <>0 ) then kt:=false;
        if kt then writeln(n,'  chia het cho cac chu so cua no')
        else write(n,'khong chia het cho ca chu so cua no');
        readln
end.