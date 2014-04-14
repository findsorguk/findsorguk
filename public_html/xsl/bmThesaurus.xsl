<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
        xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
        xmlns:sp="http://www.w3.org/2005/sparql-results#"
        xmlns="http://www.w3.org/1999/xhtml">

  <xsl:template match="sp:sparql">
    <html>
      <head><title>British Museum Thesauri from Sparql</title>
      <link rel="stylesheet" href="../css/bootstrap.css" type="text/css" />
      </head>
      <body>
        <table class="table table-striped" >
          <tr>
            <th>Subject</th>
            <th>Label</th>
            <th>Scope note</th>
          </tr>
          <xsl:apply-templates/>
        </table>
      </body>
    </html>
  </xsl:template>

  <xsl:template match="sp:result">
    <tr>
      <td><xsl:value-of select="sp:binding[@name='subject']"/></td>
      <td><xsl:value-of select="sp:binding[@name='label']"/></td>
      <td><xsl:value-of select="sp:binding[@name='note']"/></td>
    </tr>
  </xsl:template>

</xsl:stylesheet>